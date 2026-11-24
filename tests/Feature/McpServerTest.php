<?php

namespace Tests\Feature;

use App\Models\McpAccessToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\Support\McpSchema;
use Tests\TestCase;

class McpServerTest extends TestCase
{
    private string $dbPath;

    private string $uiFlagPath;

    private string $staffToken;

    private string $clerkToken;

    private string $clientToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dbPath = sys_get_temp_dir().'/wwt-mcp-'.getmypid().'.sqlite';
        if (is_file($this->dbPath)) {
            unlink($this->dbPath);
        }
        touch($this->dbPath);

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', $this->dbPath);
        config()->set('database.connections.sqlite.foreign_key_constraints', false);
        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');
        DB::reconnect('sqlite');
        config()->set('mcp.enabled', true);
        config()->set('mcp.rate_limit_per_minute', 1000);
        config()->set('mcp.timeout_seconds', 15);
        config()->set('app.timezone', 'America/New_York');
        Carbon::setTestNow(Carbon::parse('2026-11-12 15:00:00', 'America/New_York'));
        $this->uiFlagPath = storage_path('app/mcp/ui-enabled');
        if (is_file($this->uiFlagPath)) {
            unlink($this->uiFlagPath);
        }

        McpSchema::create();
        $this->seedCargo();
        $this->staffToken = $this->makeToken('staff', 1);
        $this->clerkToken = $this->makeToken('staff', 2);
        $this->clientToken = $this->makeToken('client', 1);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        DB::disconnect('sqlite');
        if (isset($this->dbPath) && is_file($this->dbPath)) {
            @unlink($this->dbPath);
        }
        if (isset($this->uiFlagPath) && is_file($this->uiFlagPath)) {
            @unlink($this->uiFlagPath);
        }
        parent::tearDown();
    }

    public function test_mcp_endpoint_is_inaccessible_when_disabled_and_works_when_enabled(): void
    {
        config()->set('mcp.enabled', false);
        DB::table('mcp_audit_logs')->delete();

        $disabled = $this->mcp($this->staffToken, 'initialize', [
            'protocolVersion' => '2025-03-26',
            'capabilities' => [],
            'clientInfo' => ['name' => 'aide-test', 'version' => '1'],
        ]);
        $disabled->assertStatus(503);
        $this->assertSame('mcp_disabled', $disabled->json('error.data.code'));
        $this->assertSame(0, DB::table('mcp_audit_logs')->count());
        $this->assertSame(0, DB::table('mcp_access_tokens')->whereNotNull('last_used_at')->count());

        config()->set('mcp.enabled', true);
        $enabled = $this->mcp($this->staffToken, 'ping');
        $enabled->assertOk();
        $this->assertSame([], $enabled->json('result'));
    }

    public function test_settings_ui_toggle_disables_mcp_without_changing_env_master(): void
    {
        config()->set('mcp.enabled', true);
        \App\Mcp\McpFeature::setUiEnabled(false);

        $disabled = $this->mcp($this->staffToken, 'ping');
        $disabled->assertStatus(503);
        $this->assertSame('mcp_disabled', $disabled->json('error.data.code'));

        \App\Mcp\McpFeature::setUiEnabled(true);
        $this->mcp($this->staffToken, 'ping')->assertOk();
    }

    public function test_settings_page_toggle_endpoint_updates_runtime_flag(): void
    {
        config()->set('mcp.enabled', true);
        $user = \App\User::query()->find(1);
        $this->actingAs($user, 'web');

        $off = $this->postJson('/admin/mcp-settings', ['enabled' => false]);
        $off->assertOk();
        $this->assertFalse($off->json('mcp.active'));
        $this->assertFalse(\App\Mcp\McpFeature::isEnabled());

        $on = $this->postJson('/admin/mcp-settings', ['enabled' => true]);
        $on->assertOk();
        $this->assertTrue($on->json('mcp.active'));

        config()->set('mcp.enabled', false);
        $blocked = $this->postJson('/admin/mcp-settings', ['enabled' => true]);
        $blocked->assertStatus(422);
        $this->assertFalse(\App\Mcp\McpFeature::envEnabled());
    }

    public function test_initialize_lists_tools_and_reads_a_resource(): void
    {
        $init = $this->mcp($this->staffToken, 'initialize', [
            'protocolVersion' => '2025-03-26',
            'capabilities' => [],
            'clientInfo' => ['name' => 'aide-test', 'version' => '1'],
        ]);
        $init->assertOk();
        $this->assertSame('2025-03-26', $init->json('result.protocolVersion'));
        $this->assertFalse($init->json('result.capabilities.tools.listChanged'));

        $negotiated = $this->mcp($this->staffToken, 'initialize', [
            'protocolVersion' => '1999-01-01',
            'capabilities' => [],
            'clientInfo' => ['name' => 'aide-test', 'version' => '1'],
        ]);
        $this->assertSame('2025-03-26', $negotiated->json('result.protocolVersion'));

        $tools = $this->mcp($this->staffToken, 'tools/list');
        $tools->assertOk();
        $names = array_column($tools->json('result.tools'), 'name');
        $this->assertContains('wwt_get_cargo_overview', $names);
        $this->assertTrue($tools->json('result.tools.0.annotations.readOnlyHint'));

        $resource = $this->mcp($this->staffToken, 'resources/read', ['uri' => 'wwt://context/package-statuses']);
        $resource->assertOk();
        $body = json_decode($resource->json('result.contents.0.text'), true);
        $this->assertArrayHasKey('on-the-way', $body['automatic_value']);
        $this->assertSame('received', array_key_first($body['manual_values']));

        $this->mcp($this->staffToken, 'ping')->assertOk()->assertJsonPath('result', []);
    }

    public function test_notification_get_and_unknown_method(): void
    {
        $this->call('POST', '/mcp', [], [], [], $this->headers($this->staffToken), json_encode([
            'jsonrpc' => '2.0',
            'method' => 'notifications/initialized',
        ]))->assertStatus(202);

        $this->get('/mcp')->assertStatus(405);
        $this->delete('/mcp')->assertStatus(405);

        $unknown = $this->mcp($this->staffToken, 'prompts/list');
        $unknown->assertOk();
        $this->assertSame(-32601, $unknown->json('error.code'));
    }

    public function test_invalid_json_and_invalid_tool_params(): void
    {
        $bad = $this->call('POST', '/mcp', [], [], [], $this->headers('not-a-token'), '{');
        $bad->assertStatus(400);
        $this->assertSame(-32700, $bad->json('error.code'));
        $this->assertStringNotContainsString('not-a-token', $bad->getContent());

        $invalid = $this->mcp($this->staffToken, 'tools/call', [
            'name' => 'wwt_list_packages',
            'arguments' => ['page' => 0],
        ]);
        $invalid->assertOk();
        $this->assertTrue($invalid->json('result.isError'));
        $this->assertSame('invalid_params', $invalid->json('result.structuredContent.error.code'));
    }

    public function test_authentication_and_workspace_binding(): void
    {
        $this->call('POST', '/mcp', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json, text/event-stream',
        ], json_encode(['jsonrpc' => '2.0', 'id' => 1, 'method' => 'ping']))
            ->assertStatus(401)
            ->assertJsonPath('error.data.code', 'unauthenticated');

        $this->mcp('missing-token', 'ping')->assertStatus(401)->assertJsonPath('error.data.code', 'invalid_token');

        $revoked = $this->makeToken('staff', 1, 'ws-revoked', ['revoked_at' => Carbon::now()]);
        $this->mcp($revoked, 'ping', [], 'ws-revoked')->assertStatus(401)->assertJsonPath('error.data.code', 'revoked_token');

        $expired = $this->makeToken('staff', 1, 'ws-expired', ['expires_at' => Carbon::now()->subMinute()]);
        $this->mcp($expired, 'ping', [], 'ws-expired')->assertStatus(401)->assertJsonPath('error.data.code', 'expired_token');

        $this->mcp($this->staffToken, 'ping', [], 'other-workspace')->assertStatus(403)->assertJsonPath('error.data.code', 'workspace_mismatch');

        $this->mcp($this->makeToken('staff', 3, 'ws-password'), 'ping', [], 'ws-password')
            ->assertStatus(403)
            ->assertJsonPath('error.data.code', 'password_change_required');

        $this->mcp($this->makeToken('client', 3, 'ws-unverified'), 'ping', [], 'ws-unverified')
            ->assertStatus(403)
            ->assertJsonPath('error.data.code', 'account_unverified');
    }

    public function test_designation_and_customer_isolation(): void
    {
        $denied = $this->mcp($this->clerkToken, 'tools/call', [
            'name' => 'wwt_search_clients',
            'arguments' => ['q' => 'Own'],
        ]);
        $this->assertTrue($denied->json('result.isError'));
        $this->assertSame('forbidden', $denied->json('result.structuredContent.error.code'));

        $clerkTools = array_column($this->mcp($this->clerkToken, 'tools/list')->json('result.tools'), 'name');
        $this->assertNotContains('wwt_search_clients', $clerkTools);
        $this->assertContains('wwt_list_packages', $clerkTools);

        $cross = $this->mcp($this->clientToken, 'tools/call', [
            'name' => 'wwt_get_package',
            'arguments' => ['waybill' => 'MI-A-120011-0002'],
        ]);
        $this->assertTrue($cross->json('result.isError'));
        $this->assertSame('not_found', $cross->json('result.structuredContent.error.code'));
        $this->assertStringNotContainsString('Other', $cross->getContent());

        $self = $this->mcp($this->clientToken, 'tools/call', [
            'name' => 'wwt_get_client',
            'arguments' => ['suite' => 'WWC02'],
        ]);
        $this->assertFalse($self->json('result.isError'));
        $this->assertSame('Own', $self->json('result.structuredContent.data.first_name'));
        $this->assertSame('WWC01', $self->json('result.structuredContent.data.suite'));

        $packages = $this->mcp($this->clientToken, 'tools/call', [
            'name' => 'wwt_get_client_packages',
            'arguments' => ['id' => 2, 'suite' => 'WWC02'],
        ]);
        $this->assertSame(2, $packages->json('result.structuredContent.total_matching'));
        $this->assertStringNotContainsString('WWC02', $packages->getContent());
    }

    public function test_package_queries_match_existing_counts(): void
    {
        $overview = $this->mcp($this->staffToken, 'tools/call', [
            'name' => 'wwt_get_cargo_overview',
            'arguments' => ['date' => '2026-11-12'],
        ]);
        $data = $overview->json('result.structuredContent.data');
        $this->assertSame(DB::table('clients')->whereDate('created_at', '<=', '2026-11-12')->count(), $data['active_clients']);
        $this->assertSame(DB::table('packages')->whereDate('created_at', '2026-11-12')->count(), $data['packages_created']);
        $this->assertSame(
            DB::table('packages')->where('status', 'retired')->whereDate('status_change_date', '2026-11-12')->count(),
            $data['packages_retired']
        );
        $this->assertEquals(4.0, $data['retired_weight_kg']);

        $page = $this->mcp($this->staffToken, 'tools/call', [
            'name' => 'wwt_list_packages',
            'arguments' => ['status' => 'received', 'payment_status' => 0, 'per_page' => 1],
        ]);
        $this->assertSame(1, $page->json('result.structuredContent.count'));
        $this->assertSame(1, $page->json('result.structuredContent.total_matching'));
        $this->assertFalse($page->json('result.structuredContent.has_more'));
        $this->assertSame('MI A 120011 0001', $page->json('result.structuredContent.data.0.waybill'));
        $this->assertSame('unpaid', $page->json('result.structuredContent.data.0.payment_state'));
        $this->assertSame(10, $page->json('result.structuredContent.data.0.weight_kg'));

        $missingWeight = $this->mcp($this->staffToken, 'tools/call', [
            'name' => 'wwt_get_package',
            'arguments' => ['waybill' => 'MI A 110011 0001'],
        ]);
        $this->assertNull($missingWeight->json('result.structuredContent.data.weight_kg'));

        $recent = $this->mcp($this->staffToken, 'tools/call', [
            'name' => 'wwt_get_recent_packages',
            'arguments' => ['start_date' => '2026-11-12', 'end_date' => '2026-11-12'],
        ]);
        $this->assertSame(2, $recent->json('result.structuredContent.total_matching'));

        $history = $this->mcp($this->staffToken, 'tools/call', [
            'name' => 'wwt_get_package_status_history',
            'arguments' => ['waybill' => 'MI-A-120011-0001'],
        ]);
        $this->assertFalse($history->json('result.structuredContent.data.history_available'));
        $this->assertSame('received', $history->json('result.structuredContent.data.current.status'));

        $missing = $this->mcp($this->staffToken, 'tools/call', [
            'name' => 'wwt_track_waybill',
            'arguments' => ['waybill' => 'DOES-NOT-EXIST'],
        ]);
        $this->assertSame('not_found', $missing->json('result.structuredContent.error.code'));

        $stats = $this->mcp($this->staffToken, 'tools/call', [
            'name' => 'wwt_get_package_statistics',
            'arguments' => [],
        ]);
        $this->assertSame(3, $stats->json('result.structuredContent.data.package_count'));
        $this->assertEquals((float) DB::table('packages')->sum('kg'), $stats->json('result.structuredContent.data.total_kg'));
        $this->assertEquals((float) DB::table('packages')->sum('grand_total'), $stats->json('result.structuredContent.data.total_grand_total'));

        $weight = $this->mcp($this->staffToken, 'tools/call', [
            'name' => 'wwt_get_weight_volume_summary',
            'arguments' => ['statuses' => ['received']],
        ]);
        $this->assertSame(1, $weight->json('result.structuredContent.data.package_count'));
        $this->assertEquals(10.0, $weight->json('result.structuredContent.data.total_kg'));
        $this->assertEquals(0.2, $weight->json('result.structuredContent.data.total_cbm'));

        $payments = $this->mcp($this->staffToken, 'tools/call', [
            'name' => 'wwt_get_payment_status_summary',
            'arguments' => [],
        ]);
        $this->assertSame(1, $payments->json('result.structuredContent.data.paid'));
        $this->assertSame(1, $payments->json('result.structuredContent.data.unpaid'));
        $this->assertSame(1, $payments->json('result.structuredContent.data.unknown'));

        $report = $this->mcp($this->staffToken, 'tools/call', [
            'name' => 'wwt_get_operational_report',
            'arguments' => ['per_page' => 1, 'page' => 1],
        ]);
        $this->assertSame(1, $report->json('result.structuredContent.count'));
        $this->assertSame(3, $report->json('result.structuredContent.total_matching'));
        $this->assertTrue($report->json('result.structuredContent.has_more'));
        $this->assertEquals((float) DB::table('packages')->sum('kg'), $report->json('result.structuredContent.total_kg'));
    }

    public function test_rates_origins_estimate_and_organization(): void
    {
        $estimate = $this->mcp($this->staffToken, 'tools/call', [
            'name' => 'wwt_estimate_freight',
            'arguments' => ['origin_id' => 1, 'kg' => 10, 'insured' => true],
        ]);
        $this->assertEquals(9.0, $estimate->json('result.structuredContent.data.rate_per_kg'));
        $this->assertEquals(99.0, $estimate->json('result.structuredContent.data.grand_total'));
        $this->assertFalse($estimate->json('result.structuredContent.data.additional_column_applied'));

        $zero = $this->mcp($this->staffToken, 'tools/call', [
            'name' => 'wwt_estimate_freight',
            'arguments' => ['origin_id' => 1, 'kg' => 0],
        ]);
        $this->assertEquals(0, $zero->json('result.structuredContent.data.grand_total'));

        $origins = $this->mcp($this->clientToken, 'tools/call', [
            'name' => 'wwt_list_origins',
            'arguments' => [],
        ]);
        $this->assertSame('Miami', $origins->json('result.structuredContent.data.0.origin_name'));

        $rates = $this->mcp($this->clientToken, 'tools/call', [
            'name' => 'wwt_get_freight_rates',
            'arguments' => [],
        ]);
        $this->assertSame(1, $rates->json('result.structuredContent.total_matching'));

        $org = $this->mcp($this->clientToken, 'tools/call', [
            'name' => 'wwt_get_organization_profile',
            'arguments' => [],
        ]);
        $this->assertSame('World Wide Trading Group', $org->json('result.structuredContent.data.name'));
        $this->assertArrayNotHasKey('vat_number', $org->json('result.structuredContent.data'));

        $staffOrg = $this->mcp($this->staffToken, 'tools/call', [
            'name' => 'wwt_get_organization_profile',
            'arguments' => [],
        ]);
        $this->assertSame('VAT-1', $staffOrg->json('result.structuredContent.data.vat_number'));
    }

    public function test_public_tracking_contract_is_unchanged(): void
    {
        $response = $this->getJson('/api/track/MI-A-120011-0001');
        $response->assertOk();
        $this->assertSame(['waybill', 'status', 'kg', 'cbm', 'created_at'], array_keys($response->json('data')));
        $this->assertStringNotContainsString('WWC01', $response->getContent());
        $this->assertStringNotContainsString('payment', $response->getContent());
    }

    public function test_database_error_timeout_rate_limit_and_audit_redaction(): void
    {
        DB::table('mcp_audit_logs')->delete();
        $this->mcp($this->staffToken, 'tools/call', [
            'name' => 'wwt_get_package',
            'arguments' => ['waybill' => 'MI-A-120011-0001'],
        ])->assertOk();
        $audit = DB::table('mcp_audit_logs')->first();
        $this->assertSame('wwt_get_package', $audit->tool);
        $this->assertTrue((bool) $audit->ok);
        $this->assertStringNotContainsString($this->staffToken, json_encode($audit));

        SchemaDropPackages::drop();
        $failed = $this->mcp($this->staffToken, 'tools/call', [
            'name' => 'wwt_list_packages',
            'arguments' => [],
        ]);
        $this->assertTrue($failed->json('result.isError'));
        $this->assertSame('database_error', $failed->json('result.structuredContent.error.code'));
        $this->assertStringNotContainsString('SQLSTATE', $failed->getContent());
        $this->assertStringNotContainsString($this->staffToken, $failed->getContent());

        config()->set('mcp.timeout_seconds', 0);
        $timeout = $this->mcp($this->staffToken, 'tools/call', [
            'name' => 'wwt_get_cargo_overview',
            'arguments' => [],
        ]);
        $this->assertSame('timeout', $timeout->json('result.structuredContent.error.code'));

        config()->set('mcp.timeout_seconds', 15);
        config()->set('mcp.rate_limit_per_minute', 1);
        $limited = $this->makeToken('staff', 1, 'ws-limit');
        $this->mcp($limited, 'ping', [], 'ws-limit')->assertOk();
        $this->mcp($limited, 'ping', [], 'ws-limit')->assertStatus(429)->assertJsonPath('error.data.code', 'rate_limited');
    }

    public function test_issue_and_revoke_token_command(): void
    {
        Artisan::call('mcp:issue-token', [
            '--actor' => 'staff',
            '--id' => 1,
            '--workspace' => 'ws-command',
            '--label' => 'aide',
            '--days' => 7,
        ]);
        $output = Artisan::output();
        $this->assertStringContainsString('Token (shown once):', $output);
        preg_match('/Token \(shown once\):\s*([a-f0-9]{64})/', $output, $matches);
        $plain = $matches[1];
        $this->mcp($plain, 'ping', [], 'ws-command')->assertOk();

        $id = McpAccessToken::query()->where('aide_workspace_id', 'ws-command')->value('id');
        Artisan::call('mcp:revoke-token', ['id' => $id]);
        $this->mcp($plain, 'ping', [], 'ws-command')->assertStatus(401)->assertJsonPath('error.data.code', 'revoked_token');
    }

    private function seedCargo(): void
    {
        DB::table('users')->insert([
            ['id' => 1, 'first_name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'hashed', 'designation' => 1, 'super' => 1, 'password_changed' => '1', 'created_at' => '2026-11-12 09:00:00', 'updated_at' => '2026-11-12 09:00:00'],
            ['id' => 2, 'first_name' => 'Clerk', 'email' => 'clerk@example.com', 'password' => 'hashed', 'designation' => 2, 'super' => 0, 'password_changed' => '1', 'created_at' => '2026-11-12 09:00:00', 'updated_at' => '2026-11-12 09:00:00'],
            ['id' => 3, 'first_name' => 'New', 'email' => 'new@example.com', 'password' => 'hashed', 'designation' => 1, 'super' => 1, 'password_changed' => '0', 'created_at' => '2026-11-12 09:00:00', 'updated_at' => '2026-11-12 09:00:00'],
        ]);
        DB::table('branches')->insert(['id' => 1, 'branch' => 'Asuncion', 'status' => 1]);
        DB::table('clients')->insert([
            ['id' => 1, 'first_name' => 'Own', 'last_name' => 'Customer', 'company_name' => null, 'email' => 'own@example.com', 'password' => 'hashed', 'suite' => 'WWC01', 'phone' => '111', 'client_type' => 'person', 'branch_id' => 1, 'email_verified_at' => '2026-11-12 09:00:00', 'created_at' => '2026-11-12 09:00:00', 'updated_at' => '2026-11-12 09:00:00'],
            ['id' => 2, 'first_name' => 'Other', 'last_name' => 'Customer', 'company_name' => null, 'email' => 'other@example.com', 'password' => 'hashed', 'suite' => 'WWC02', 'phone' => '222', 'client_type' => 'person', 'branch_id' => 1, 'email_verified_at' => '2026-11-12 09:00:00', 'created_at' => '2026-11-12 09:00:00', 'updated_at' => '2026-11-12 09:00:00'],
            ['id' => 3, 'first_name' => 'Pending', 'last_name' => 'Customer', 'company_name' => null, 'email' => 'pending@example.com', 'password' => 'hashed', 'suite' => 'WWC03', 'phone' => '333', 'client_type' => 'person', 'branch_id' => 1, 'email_verified_at' => null, 'created_at' => '2026-11-12 09:00:00', 'updated_at' => '2026-11-12 09:00:00'],
        ]);
        DB::table('countries')->insert(['id' => 1, 'name' => 'United States', 'iso' => 'US']);
        DB::table('origins')->insert([
            'id' => 1,
            'origin_name' => 'Miami',
            'address' => '1 Port Rd',
            'email' => 'miami@example.com',
            'zip_code' => '33101',
            'phone' => '305',
            'country_id' => 1,
        ]);
        DB::table('freight_rates')->insert([
            ['id' => 1, 'branch_id' => 1, 'client_id' => null, 'origin_id' => 1, 'rate' => 2.50, 'additional' => 1.00, 'type' => 'air'],
            ['id' => 2, 'branch_id' => 1, 'client_id' => 2, 'origin_id' => 1, 'rate' => 9.00, 'additional' => 0, 'type' => 'air'],
        ]);
        DB::table('packages')->insert([
            ['id' => 1, 'waybill' => 'MI A 120011 0001', 'original_tracking' => 'TRK1', 'client_id' => 1, 'client_suite' => 'WWC01', 'client_name' => 'Own Customer', 'origin_id' => 1, 'type' => 'air', 'description' => 'Boxes', 'comments' => null, 'is_insured' => 'not-insured', 'kg' => 10, 'cbm' => 0.2, 'grand_total' => 25, 'status' => 'received', 'status_change_date' => '2026-11-12 10:00:00', 'payment_status' => 0, 'created_at' => '2026-11-12 10:00:00', 'updated_at' => '2026-11-12 10:00:00'],
            ['id' => 2, 'waybill' => 'MI A 120011 0002', 'original_tracking' => 'TRK2', 'client_id' => 2, 'client_suite' => 'WWC02', 'client_name' => 'Other Customer', 'origin_id' => 1, 'type' => 'air', 'description' => 'Parts', 'comments' => null, 'is_insured' => 'insured', 'kg' => 4, 'cbm' => 0.1, 'grand_total' => 10, 'status' => 'retired', 'status_change_date' => '2026-11-12 12:00:00', 'payment_status' => 1, 'created_at' => '2026-11-12 11:00:00', 'updated_at' => '2026-11-12 11:00:00'],
            ['id' => 3, 'waybill' => 'MI A 110011 0001', 'original_tracking' => 'TRK3', 'client_id' => 1, 'client_suite' => 'WWC01', 'client_name' => 'Own Customer', 'origin_id' => 1, 'type' => 'maritime', 'description' => 'Pending weight', 'comments' => null, 'is_insured' => 'not-insured', 'kg' => null, 'cbm' => null, 'grand_total' => null, 'status' => 'on-the-way', 'status_change_date' => null, 'payment_status' => null, 'created_at' => '2026-11-11 10:00:00', 'updated_at' => null],
        ]);
        DB::table('organizations')->insert([
            'name' => 'World Wide Trading Group',
            'phone' => '555',
            'email' => 'hello@example.com',
            'address' => 'Asuncion',
            'postal_code' => '1209',
            'registration_number' => 'REG-1',
            'vat_number' => 'VAT-1',
        ]);
        DB::table('organization_phone_no')->insert(['type' => 'office', 'phone_number' => '555']);

        $slugs = ['admin/home', 'admin/packages', 'admin/clients', 'admin/origins', 'admin/freight-rates', 'admin/reporting', 'admin/organization-create', 'admin/manage_settings'];
        foreach ($slugs as $index => $slug) {
            DB::table('controllers')->insert(['id' => $index + 1, 'controller' => $slug]);
        }
        DB::table('controller_designation_assignments')->insert([
            'designation_id' => 2,
            'controller_name' => 'admin/packages',
        ]);
    }

    private function makeToken(string $actor, int $id, string $workspace = 'ws-1', array $extra = []): string
    {
        $plain = hash('sha256', $actor.'|'.$id.'|'.$workspace.'|'.json_encode($extra));
        McpAccessToken::query()->create([
            'token_hash' => hash('sha256', $plain),
            'label' => 'test',
            'actor_type' => $actor,
            'actor_id' => $id,
            'aide_workspace_id' => $workspace,
            'expires_at' => $extra['expires_at'] ?? null,
            'revoked_at' => $extra['revoked_at'] ?? null,
        ]);

        return $plain;
    }

    private function headers(string $token, string $workspace = 'ws-1'): array
    {
        return [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json, text/event-stream',
            'HTTP_AUTHORIZATION' => 'Bearer '.$token,
            'HTTP_X_WWT_WORKSPACE' => $workspace,
        ];
    }

    private function mcp(string $token, string $method, array $params = [], string $workspace = 'ws-1')
    {
        return $this->call('POST', '/mcp', [], [], [], $this->headers($token, $workspace), json_encode([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => $method,
            'params' => $params,
        ]));
    }
}

class SchemaDropPackages
{
    public static function drop(): void
    {
        Schema::drop('packages');
    }
}
