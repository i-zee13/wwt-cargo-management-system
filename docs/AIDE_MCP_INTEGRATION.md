# Aide connection guide for the WWT MCP server

The Aide repository was not in this workspace. This is the server contract and a client Aide can copy. It does not mean an Aide workspace is already connected.

## Feature flag

`MCP_ENABLED` defaults to `false`. While it is false, `POST /mcp` returns HTTP 503 with `mcp_disabled` and does not authenticate, query cargo tables, or run tools.

After `MCP_ENABLED=true`, staff can still turn the endpoint off from **Admin → Settings → Aide MCP** without changing `.env`. That UI flag is stored in `storage/app/mcp/ui-enabled`, not in the database.

## Endpoint

`POST {WWT_APP_URL}/mcp`

Transport: stateless MCP Streamable HTTP. One JSON-RPC message per POST, or a JSON-RPC batch. The success body is `application/json`. This server does not return `text/event-stream` and does not issue `Mcp-Session-Id`.

`GET /mcp` and `DELETE /mcp` return `405`.

Send:

```http
POST /mcp HTTP/1.1
Authorization: Bearer <token>
X-Wwt-Workspace: <aide-workspace-id>
Accept: application/json, text/event-stream
Content-Type: application/json
```

The workspace header is the workspace bound to the token when it was issued. Do not take a workspace id from the model.

## Credentials

1. Run the new migrations on the WWT database: `php artisan migrate`. They only create `mcp_access_tokens` and `mcp_audit_logs`. They were not run as part of this implementation.
2. Issue a token for one existing staff user or verified customer:

```bash
php artisan mcp:issue-token --actor=staff --id=USER_ID --workspace=AIDE_WORKSPACE_ID --label=aide-prod --days=30
```

3. Store the printed token in the Aide connector secret store. It is not shown again. Do not put it in a prompt, a log line, or a tool result.
4. Rotate by issuing a new token, switching the connector, then `php artisan mcp:revoke-token TOKEN_ID`.
5. A customer token requires `clients.email_verified_at`. A staff token requires `password_changed != 0`.

Environment:

- `MCP_ENABLED` — master switch, default `false`.
- `WWT_MCP_RATE_LIMIT` — requests per minute per token, default 60.
- `WWT_MCP_TIMEOUT` — seconds allowed around a tool call, default 15. This does not cancel a SQL statement already sent to MySQL.

## Identity mapping

| Aide | WWT |
| --- | --- |
| Workspace id | `mcp_access_tokens.aide_workspace_id` |
| Connector secret | SHA-256 hash of the bearer token |
| Staff connector | `actor_type=staff`, `actor_id=users.id` |
| Customer connector | `actor_type=client`, `actor_id=clients.id` |

WWT enforces designation rights and customer `client_id` scope. Two workspaces do not see different databases. A token simply cannot be presented for a workspace it was not issued to. Data scope is the WWT user or customer on the token.

## Tools and resources

Tool names, inputs, and staff slugs are in `docs/WWT_MCP_TOOL_CATALOG.md`.

Resources:

- `wwt://context/overview`
- `wwt://context/packages`
- `wwt://context/package-statuses`
- `wwt://context/payment-statuses`
- `wwt://context/origins-and-rates`
- `wwt://context/reporting`
- `wwt://context/capabilities`

## Calls

Initialize:

```json
{"jsonrpc":"2.0","id":1,"method":"initialize","params":{"protocolVersion":"2025-03-26","capabilities":{},"clientInfo":{"name":"aide","version":"1"}}}
```

Supported protocol versions: `2025-03-26` and `2025-06-18`. An older requested version receives `2025-03-26`.

List tools: `tools/list`. The list is already filtered for the token.

Call:

```json
{"jsonrpc":"2.0","id":2,"method":"tools/call","params":{"name":"wwt_get_cargo_overview","arguments":{}}}
```

The tool payload is `result.structuredContent`. `result.isError: true` means the tool refused or failed. `result.content[0].text` repeats that payload as text.

Health check: method `ping`. Result is `{}`.

Notifications such as `notifications/initialized` have no `id` and receive HTTP 202 with an empty body.

## Errors

| HTTP | `error.data.code` | What Aide should do |
| --- | --- | --- |
| 401 | `unauthenticated`, `invalid_token` | Drop the secret from model context. Mark the connector disconnected. |
| 401 | `revoked_token`, `expired_token` | Stop calling. Ask an admin to issue a new token. |
| 403 | `workspace_mismatch` | Do not retry with a model-supplied workspace. |
| 403 | `password_change_required`, `account_unverified` | The WWT account cannot use MCP until that account state changes. |
| 406 | | Send `Accept: application/json, text/event-stream`. |
| 429 | `rate_limited` | Back off. Honor `Retry-After` when present. |
| 503 | `credential_store_unavailable` | Migrations have not been applied. |
| 200 | tool `forbidden` | Hide that tool for this identity. |
| 200 | tool `not_found` | Tell the user the waybill or customer was not found. Do not treat that as proof another customer owns it. |
| 200 | tool `timeout`, `database_error` | Retry once. The message does not include SQL or the token. |

JSON-RPC `-32700` parse error, `-32600` invalid request, `-32601` unknown method, `-32602` invalid params.

## Deploy

The route is registered from `routes/mcp.php` in `bootstrap/app.php` and is outside the web middleware group, so it does not use the session cookie or CSRF. Keep it on the Laravel host. Do not proxy it through the Next.js site.

Call it from the Aide worker, not from a browser. Browser CORS is not enabled for `/mcp`.

## Client example

`examples/aide/wwt-mcp-client.ts` is the Worker-side reference. It initializes, lists tools, and calls `wwt_get_cargo_overview`.

## Security limits

- Read-only. No package edits, no Stripe charges, no email sends.
- Public tracking is unchanged and is not served by this endpoint.
- The freight estimate uses the package form's origin-wide maximum rate. That number can come from a rate row linked to another customer. The rate list does not return those other rows to a customer.
- The timeout wraps the tool call. It does not interrupt one running MySQL statement.
- Audit rows store the tool name and actor, not the bearer token and not the package payload.
