<?php

namespace App\Mcp;

use App\Models\McpAccessToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class McpAuthenticator
{
    public function authenticate(Request $request): McpActor
    {
        $plain = $request->bearerToken();
        if ($plain === null || $plain === '') {
            throw new McpAuthException(401, -32001, 'unauthenticated', 'Authentication is required.');
        }

        if (! Schema::hasTable('mcp_access_tokens')) {
            throw new McpAuthException(503, -32003, 'credential_store_unavailable', 'The MCP credential store is not installed.');
        }

        $token = McpAccessToken::query()->where('token_hash', hash('sha256', $plain))->first();
        if (! $token) {
            throw new McpAuthException(401, -32001, 'invalid_token', 'The access token is invalid.');
        }

        if ($token->revoked_at !== null) {
            throw new McpAuthException(401, -32001, 'revoked_token', 'The access token has been revoked.');
        }

        if ($token->expires_at !== null && $token->expires_at->isPast()) {
            throw new McpAuthException(401, -32001, 'expired_token', 'The access token has expired.');
        }

        $workspace = trim((string) $request->header((string) config('mcp.workspace_header')));
        if ($workspace === '' || ! hash_equals((string) $token->aide_workspace_id, $workspace)) {
            throw new McpAuthException(403, -32002, 'workspace_mismatch', 'The workspace is not authorized for this token.');
        }

        $actor = $this->actor($token);
        $token->forceFill(['last_used_at' => Carbon::now()])->save();

        return $actor;
    }

    private function actor(McpAccessToken $token): McpActor
    {
        try {
            if ($token->actor_type === 'staff') {
                $user = DB::table('users')->where('id', $token->actor_id)->first();
                if (! $user) {
                    throw new McpAuthException(401, -32001, 'invalid_token', 'The access token is invalid.');
                }
                if ($user->password_changed == 0) {
                    throw new McpAuthException(403, -32003, 'password_change_required', 'This staff account must change its password before using MCP.');
                }

                return new McpActor(
                    (int) $token->id,
                    'staff',
                    (int) $user->id,
                    (string) $token->aide_workspace_id,
                    $user->designation ?? null,
                    (int) ($user->super ?? 0) === 1,
                );
            }

            if ($token->actor_type === 'client') {
                $client = DB::table('clients')->where('id', $token->actor_id)->first();
                if (! $client) {
                    throw new McpAuthException(401, -32001, 'invalid_token', 'The access token is invalid.');
                }
                if ($client->email_verified_at === null) {
                    throw new McpAuthException(403, -32003, 'account_unverified', 'This customer account is not email-verified.');
                }

                return new McpActor(
                    (int) $token->id,
                    'client',
                    (int) $client->id,
                    (string) $token->aide_workspace_id,
                );
            }
        } catch (McpAuthException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new McpAuthException(503, -32003, 'credential_store_unavailable', 'The MCP credential store is not available.');
        }

        throw new McpAuthException(401, -32001, 'invalid_token', 'The access token is invalid.');
    }
}
