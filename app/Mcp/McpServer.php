<?php

namespace App\Mcp;

use App\Models\McpAuditLog;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Throwable;

class McpServer
{
    public function __construct(
        private McpAuthenticator $authenticator,
        private ToolCatalog $tools,
        private DesignationAuthorizer $authorizer,
        private CargoReader $reader,
        private ContextCatalog $resources,
    ) {}

    public function handle(Request $request): JsonResponse|Response
    {
        $secret = $request->bearerToken();
        if (! $this->acceptsJson($request)) {
            return $this->errorResponse(null, -32000, 'Not Acceptable', 406, $secret);
        }

        try {
            $payload = json_decode($request->getContent(), true, 64, JSON_THROW_ON_ERROR);
        } catch (Throwable) {
            return $this->errorResponse(null, -32700, 'Parse error', 400, $secret);
        }

        if (! is_array($payload)) {
            return $this->errorResponse(null, -32600, 'Invalid Request', 400, $secret);
        }

        $batch = array_is_list($payload);
        $messages = $batch ? $payload : [$payload];
        if ($messages === []) {
            return $this->errorResponse(null, -32600, 'Invalid Request', 400, $secret);
        }

        $this->limit($request, $secret);

        try {
            $actor = $this->authenticator->authenticate($request);
        } catch (McpAuthException $e) {
            $this->audit(null, null, null, false, $e->errorCode);

            return $this->errorResponse(null, $e->rpcCode, $e->getMessage(), $e->httpStatus, $secret, $e->errorCode);
        }

        $responses = [];
        $calls = 0;
        foreach ($messages as $message) {
            $result = $this->dispatch($actor, $message, $secret);
            if ($result === null) {
                continue;
            }
            $calls++;
            $responses[] = $result;
        }

        if ($calls === 0) {
            return response('', 202);
        }

        return response()->json($batch ? $responses : $responses[0]);
    }

    private function dispatch(McpActor $actor, mixed $message, ?string $secret): ?array
    {
        if (! is_array($message) || ($message['jsonrpc'] ?? null) !== '2.0' || ! is_string($message['method'] ?? null)) {
            return $this->errorBody($message['id'] ?? null, -32600, 'Invalid Request');
        }

        $method = $message['method'];
        $id = $message['id'] ?? null;
        $isNotification = ! array_key_exists('id', $message);
        $params = $message['params'] ?? [];
        if ($params === null) {
            $params = [];
        }
        if (! is_array($params)) {
            return $isNotification ? null : $this->errorBody($id, -32602, 'Invalid params');
        }

        if ($isNotification) {
            $this->audit($actor, $method, null, true, null);

            return null;
        }

        $started = microtime(true);
        try {
            $result = match ($method) {
                'initialize' => $this->initialize($params),
                'ping' => (object) [],
                'tools/list' => ['tools' => $this->tools->forActor($actor, $this->authorizer)],
                'tools/call' => $this->callTool($actor, $params, $started),
                'resources/list' => ['resources' => $this->resources->list()],
                'resources/read' => $this->readResource($params),
                default => throw new McpToolException('method_not_found', 'Method not found'),
            };
            $this->audit($actor, $method, $params['name'] ?? null, true, null);

            return ['jsonrpc' => '2.0', 'id' => $id, 'result' => $result];
        } catch (McpToolException $e) {
            $tool = is_string($params['name'] ?? null) ? $params['name'] : null;
            $this->audit($actor, $method, $tool, false, $e->errorCode);
            if ($e->errorCode === 'method_not_found') {
                return $this->errorBody($id, -32601, 'Method not found');
            }
            if ($method !== 'tools/call') {
                return $this->errorBody($id, -32602, $e->getMessage());
            }

            return $this->toolError($id, $e->errorCode, $e->getMessage());
        } catch (QueryException $e) {
            Log::warning('mcp.query_failed', ['exception' => SecretRedactor::clean($e->getMessage(), $secret)]);
            $tool = is_string($params['name'] ?? null) ? $params['name'] : null;
            $this->audit($actor, $method, $tool, false, 'database_error');

            return $this->toolError($id, 'database_error', 'The cargo database query failed.');
        } catch (Throwable $e) {
            report($e);
            $tool = is_string($params['name'] ?? null) ? $params['name'] : null;
            $this->audit($actor, $method, $tool, false, 'internal_error');

            return $this->toolError($id, 'internal_error', 'The request could not be completed.');
        }
    }

    private function initialize(array $params): array
    {
        $requested = $params['protocolVersion'] ?? null;
        if (! is_string($requested) || $requested === '') {
            throw new McpToolException('invalid_params', 'protocolVersion is required.');
        }
        $supported = config('mcp.protocol_versions');
        $version = in_array($requested, $supported, true) ? $requested : $supported[0];

        return [
            'protocolVersion' => $version,
            'capabilities' => [
                'tools' => ['listChanged' => false],
                'resources' => ['subscribe' => false, 'listChanged' => false],
            ],
            'serverInfo' => config('mcp.server'),
            'instructions' => 'Read-only access to WWT cargo operations. Customer tokens are limited to their own suite. Do not infer package history beyond the current status.',
        ];
    }

    private function callTool(McpActor $actor, array $params, float $started): array
    {
        $name = $params['name'] ?? null;
        $arguments = $params['arguments'] ?? [];
        if (! is_string($name) || $name === '') {
            throw new McpToolException('invalid_params', 'Tool name is required.');
        }
        if ($this->tools->rule($name) === null) {
            throw new McpToolException('invalid_params', 'Unknown tool.');
        }
        if (! is_array($arguments)) {
            throw new McpToolException('invalid_params', 'Tool arguments must be an object.');
        }
        if (! $this->authorizer->allows($actor, $name)) {
            throw new McpToolException('forbidden', 'This identity cannot use that tool.');
        }
        $this->deadline($started);
        $structured = $this->reader->run($actor, $name, $arguments);
        $this->deadline($started);

        return [
            'content' => [[
                'type' => 'text',
                'text' => json_encode($structured, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ]],
            'structuredContent' => $structured,
            'isError' => false,
        ];
    }

    private function readResource(array $params): array
    {
        $uri = $params['uri'] ?? null;
        if (! is_string($uri) || $uri === '') {
            throw new McpToolException('invalid_params', 'Resource uri is required.');
        }
        $content = $this->resources->read($uri);
        if ($content === null) {
            throw new McpToolException('not_found', 'That resource does not exist.');
        }

        return ['contents' => [$content]];
    }

    private function deadline(float $started): void
    {
        $seconds = (int) config('mcp.timeout_seconds');
        if ($seconds <= 0 || (microtime(true) - $started) >= $seconds) {
            throw new McpToolException('timeout', 'The cargo query exceeded the time limit.');
        }
    }

    private function limit(Request $request, ?string $secret): void
    {
        $max = (int) config('mcp.rate_limit_per_minute');
        $key = 'mcp:'.hash('sha256', ($secret ?: 'ip').'|'.$request->ip());
        if (RateLimiter::tooManyAttempts($key, max($max, 1))) {
            $this->audit(null, 'rate_limit', null, false, 'rate_limited');
            throw new McpAuthException(429, -32029, 'rate_limited', 'Too many requests.');
        }
        RateLimiter::hit($key, 60);
    }

    private function acceptsJson(Request $request): bool
    {
        $accept = (string) $request->header('Accept', '*/*');

        return str_contains($accept, 'application/json')
            || str_contains($accept, 'text/event-stream')
            || str_contains($accept, '*/*');
    }

    private function audit(?McpActor $actor, ?string $method, ?string $tool, bool $ok, ?string $error): void
    {
        try {
            McpAuditLog::query()->create([
                'token_id' => $actor?->tokenId,
                'workspace_id' => $actor?->workspaceId,
                'actor_type' => $actor?->type,
                'actor_id' => $actor?->id,
                'method' => $method,
                'tool' => $tool,
                'ok' => $ok,
                'error_code' => $error,
            ]);
        } catch (Throwable $e) {
            Log::warning('mcp.audit_failed', ['exception' => SecretRedactor::clean($e->getMessage())]);
        }
    }

    private function toolError(mixed $id, string $code, string $message): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $id,
            'result' => [
                'content' => [['type' => 'text', 'text' => $message]],
                'structuredContent' => [
                    'source' => 'wwt_cargo',
                    'error' => ['code' => $code, 'message' => $message],
                ],
                'isError' => true,
            ],
        ];
    }

    private function errorBody(mixed $id, int $code, string $message, ?string $errorCode = null): array
    {
        $error = ['code' => $code, 'message' => SecretRedactor::clean($message)];
        if ($errorCode !== null) {
            $error['data'] = ['code' => $errorCode];
        }

        return ['jsonrpc' => '2.0', 'id' => $id, 'error' => $error];
    }

    private function errorResponse(mixed $id, int $code, string $message, int $status, ?string $secret, ?string $errorCode = null): JsonResponse
    {
        $body = $this->errorBody($id, $code, SecretRedactor::clean($message, $secret), $errorCode);

        return response()->json($body, $status);
    }
}
