<?php

namespace Tests\Unit;

use App\Mcp\McpFeature;
use Tests\TestCase;

class McpFeatureTest extends TestCase
{
    private string $path;

    protected function setUp(): void
    {
        parent::setUp();
        $this->path = storage_path('app/mcp/ui-enabled');
        if (is_file($this->path)) {
            unlink($this->path);
        }
    }

    protected function tearDown(): void
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }
        parent::tearDown();
    }

    public function test_defaults_to_disabled_from_env_config(): void
    {
        config()->set('mcp.enabled', false);
        $this->assertFalse(McpFeature::isEnabled());
        $this->assertFalse(McpFeature::envEnabled());
    }

    public function test_ui_toggle_cannot_override_disabled_env(): void
    {
        config()->set('mcp.enabled', false);
        McpFeature::setUiEnabled(true);
        $this->assertFalse(McpFeature::isEnabled());
    }

    public function test_ui_toggle_can_disable_when_env_allows(): void
    {
        config()->set('mcp.enabled', true);
        $this->assertTrue(McpFeature::isEnabled());
        McpFeature::setUiEnabled(false);
        $this->assertFalse(McpFeature::isEnabled());
        McpFeature::setUiEnabled(true);
        $this->assertTrue(McpFeature::isEnabled());
    }
}
