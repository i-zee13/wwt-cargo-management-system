<?php

namespace App\Http\Controllers;

use App\Mcp\McpFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class McpSettingsController extends Controller
{
    public function update(Request $request)
    {
        if (! $this->canManageSettings()) {
            return response()->json([
                'status' => 'error',
                'msg' => 'You do not have access to settings.',
            ], 403);
        }

        $request->validate([
            'enabled' => ['required', 'boolean'],
        ]);

        if (! McpFeature::envEnabled()) {
            return response()->json([
                'status' => 'error',
                'msg' => 'MCP is blocked by MCP_ENABLED=false. Set MCP_ENABLED=true in the environment first.',
                'mcp' => McpFeature::status(),
            ], 422);
        }

        McpFeature::setUiEnabled($request->boolean('enabled'));

        return response()->json([
            'status' => 'success',
            'msg' => $request->boolean('enabled') ? 'MCP enabled.' : 'MCP disabled.',
            'mcp' => McpFeature::status(),
        ]);
    }

    private function canManageSettings(): bool
    {
        $user = Auth::guard('web')->user();
        if (! $user) {
            return false;
        }

        if ((int) ($user->super ?? 0) === 1) {
            return true;
        }

        $slug = 'admin/manage_settings';
        if (! DB::table('controllers')->where('controller', $slug)->exists()) {
            return false;
        }

        if ($user->designation === null || $user->designation === '') {
            return false;
        }

        return DB::table('controller_designation_assignments')
            ->where('controller_name', $slug)
            ->where('designation_id', $user->designation)
            ->exists();
    }
}
