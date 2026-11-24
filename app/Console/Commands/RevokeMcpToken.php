<?php

namespace App\Console\Commands;

use App\Models\McpAccessToken;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class RevokeMcpToken extends Command
{
    protected $signature = 'mcp:revoke-token {id : mcp_access_tokens.id}';

    protected $description = 'Revoke a WWT MCP token.';

    public function handle(): int
    {
        if (! Schema::hasTable('mcp_access_tokens')) {
            $this->error('The MCP credential store is not installed.');

            return 1;
        }

        $token = McpAccessToken::query()->find((int) $this->argument('id'));
        if (! $token) {
            $this->error('Token not found.');

            return 1;
        }

        $token->forceFill(['revoked_at' => Carbon::now()])->save();
        $this->info('Token '.$token->id.' revoked.');

        return 0;
    }
}
