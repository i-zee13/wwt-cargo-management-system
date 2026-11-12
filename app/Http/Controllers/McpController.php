<?php

namespace App\Http\Controllers;

use App\Mcp\McpAuthException;
use App\Mcp\McpFeature;
use App\Mcp\McpServer;
use Illuminate\Http\Request;

class McpController extends Controller
{
    public function __invoke(Request $request)
    {
        if (! McpFeature::isEnabled()) {
            return response()->json([
                'jsonrpc' => '2.0',
                'id' => null,
                'error' => [
                    'code' => -32003,
                    'message' => 'The WWT MCP server is disabled.',
                    'data' => ['code' => 'mcp_disabled'],
                ],
            ], 503);
        }

        if (! $request->isMethod('POST')) {
            return response('', 405, ['Allow' => 'POST']);
        }

        try {
            return app(McpServer::class)->handle($request);
        } catch (McpAuthException $e) {
            return response()->json([
                'jsonrpc' => '2.0',
                'id' => null,
                'error' => [
                    'code' => $e->rpcCode,
                    'message' => $e->getMessage(),
                    'data' => ['code' => $e->errorCode],
                ],
            ], $e->httpStatus);
        }
    }
}
