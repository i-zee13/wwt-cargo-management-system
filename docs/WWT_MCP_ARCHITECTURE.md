# WWT MCP architecture

Verified against the Laravel application in this repository. The public Next.js site is not a data source for this server.

## Runtime

`composer.json` requires PHP `^8.2` and Laravel `^11`. Authentication guards in `config/auth.php` are session guards only:

- `web` uses `App\User` (Passport's `HasApiTokens` is on the model, and there is no Passport guard).
- `clients` uses `App\Models\ClientsModel`.

`App\Models\User` has Sanctum tokens, but it is not the auth provider. Neither package is the login path for admin or customer pages, so the MCP does not reuse those tokens.

The MCP server is part of this Laravel app. It speaks JSON-RPC 2.0 over `POST /mcp` (stateless Streamable HTTP). `GET` and `DELETE` return `405` because the server does not open an SSE session. No session id is issued.

`MCP_ENABLED` defaults to false. When disabled, `McpController` returns 503 before resolving `McpServer`, authenticating, or querying cargo data. The settings page can store a runtime off switch in `storage/app/mcp/ui-enabled` after the env flag is true.

## Request path

1. `POST /mcp` hits `App\Http\Controllers\McpController`.
2. `App\Mcp\McpServer` checks the `Accept` header, parses JSON-RPC, rate-limits, then authenticates.
3. `App\Mcp\McpAuthenticator` hashes the bearer token with SHA-256 and loads `mcp_access_tokens`.
4. The `X-Wwt-Workspace` header must match `aide_workspace_id` on that token. A workspace id inside a tool argument is rejected as an unknown parameter.
5. Staff calls go through `App\Mcp\DesignationAuthorizer`, which uses the same tables as `CustomMiddlewareForRoutes`: `controllers.controller` and `controller_designation_assignments` (`controller_name`, `designation_id`). `users.super = 1` bypasses the designation row only after the controller slug exists. Staff with `password_changed = 0` are rejected, matching `CustomMiddleware`.
6. Customer calls are allowed only for tools marked for customers. Queries add `packages.client_id = <authenticated client id>`. A suite or id in the arguments does not select another customer.
7. `App\Mcp\CargoReader` reads the existing tables. It does not call maintenance routes and it does not write cargo data.

AJAX admin requests skip the designation middleware. MCP calls do not.

## Credential store

New tables, created by migrations that were not executed against the application database:

- `mcp_access_tokens` — hash, actor (`staff` or `client`), actor id, Aide workspace id, expiry, revocation.
- `mcp_audit_logs` — tool name, actor, success, error code. The bearer token is not stored.

Issue a token with `php artisan mcp:issue-token`. Revoke it with `php artisan mcp:revoke-token {id}`. The plaintext token is printed once.

## Package data the reader uses

From `PackageController`, `ReportingController`, `HomeController`, and `public/js/custom/packages.js`:

- Waybill lookup replaces `-` with a space before comparing `packages.waybill`.
- Status radios: `received`, `embarked`, `in-progress`, `arrived`, `retired`.
- Saving a package with no kilograms sets `on-the-way`. A new package with kilograms, or an `on-the-way` package that gains kilograms, becomes `received`.
- `changeStatus` stores the submitted status and sets `status_change_date` to now. There is no history table.
- `payment_status` `1` is paid (including Stripe success). `0` is unpaid. Null is left as missing.
- Report filters and sums follow `ReportingController::generate_report`: origin, branch, status, type, client, and `created_at` when both dates are present. Totals are kilograms and `grand_total`.
- Admin home counts follow `HomeController::index`. Customer home counts follow `HomeController::customer_index`.
- Freight estimate uses `MAX(freight_rates.rate)` for the origin, then the formula in `calculateGrandTotal`. The `additional` column is not part of that formula.

## What is intentionally not a tool

Login logs, designation matrices, email-template HTML, translation files, passwords, document numbers, barcode images, and invoice files. Public tracking stays `GET /api/track/{waybill}` and still returns only waybill, status, kg, cbm, and created_at.
