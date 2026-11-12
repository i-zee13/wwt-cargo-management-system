<?php

namespace App\Console\Commands;

use App\Models\McpAccessToken;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class IssueMcpToken extends Command
{
    protected $signature = 'mcp:issue-token
        {--actor= : staff or client}
        {--id= : users.id or clients.id}
        {--workspace= : Aide workspace id}
        {--label= : token label}
        {--days=30 : days until expiry}
        {--no-expiry : token does not expire}';

    protected $description = 'Issue a revocable WWT MCP token. The secret is printed once.';

    public function handle(): int
    {
        if (! Schema::hasTable('mcp_access_tokens')) {
            $this->error('Run the MCP migrations before issuing a token.');

            return 1;
        }

        $actor = (string) $this->option('actor');
        $id = (int) $this->option('id');
        $workspace = trim((string) $this->option('workspace'));
        $label = trim((string) $this->option('label'));
        if (! in_array($actor, ['staff', 'client'], true) || $id < 1 || $workspace === '') {
            $this->error('Required options: --actor=staff|client --id= --workspace=');

            return 1;
        }

        $exists = $actor === 'staff'
            ? DB::table('users')->where('id', $id)->exists()
            : DB::table('clients')->where('id', $id)->exists();
        if (! $exists) {
            $this->error('That '.$actor.' record does not exist.');

            return 1;
        }

        $plain = bin2hex(random_bytes(32));
        $days = (int) $this->option('days');
        McpAccessToken::query()->create([
            'token_hash' => hash('sha256', $plain),
            'label' => $label !== '' ? $label : null,
            'actor_type' => $actor,
            'actor_id' => $id,
            'aide_workspace_id' => $workspace,
            'expires_at' => $this->option('no-expiry') ? null : Carbon::now()->addDays(max($days, 1)),
        ]);

        $this->line('Token (shown once):');
        $this->line($plain);
        $this->line('Workspace header: '.$workspace);

        return 0;
    }
}
