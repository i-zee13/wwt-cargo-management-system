<?php

namespace App\Mcp;

class ToolCatalog
{
    public function definitions(): array
    {
        return [
            $this->tool('wwt_get_cargo_overview', 'Operational package counts. Staff numbers for a date match the admin home cards. Customer numbers match the customer home cards and are limited to that customer.', [
                'date' => ['type' => 'string', 'description' => 'Calendar date YYYY-MM-DD. Staff only. Defaults to today in the application timezone.'],
            ], 'admin/home', 'allow'),
            $this->tool('wwt_list_packages', 'List packages the caller is allowed to see. Supports status, payment, origin, waybill, original tracking, and created-date filters, with pagination.', [
                'status' => ['type' => 'string'],
                'payment_status' => ['type' => 'integer', 'enum' => [0, 1]],
                'origin_id' => ['type' => 'integer'],
                'suite' => ['type' => 'string', 'description' => 'Staff filter on client_suite. Ignored for a customer token.'],
                'waybill' => ['type' => 'string'],
                'original_tracking' => ['type' => 'string'],
                'start_date' => ['type' => 'string', 'description' => 'Inclusive created_at date YYYY-MM-DD.'],
                'end_date' => ['type' => 'string'],
                'page' => ['type' => 'integer', 'minimum' => 1],
                'per_page' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100],
            ], 'admin/packages', 'allow'),
            $this->tool('wwt_get_package', 'One package by waybill. Hyphens in the waybill are treated as spaces, matching the existing tracking lookup.', [
                'waybill' => ['type' => 'string'],
            ], 'admin/packages', 'allow', ['waybill']),
            $this->tool('wwt_get_packages_by_status', 'Packages whose current status equals the given value.', [
                'status' => ['type' => 'string'],
                'page' => ['type' => 'integer', 'minimum' => 1],
                'per_page' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100],
            ], 'admin/packages', 'allow', ['status']),
            $this->tool('wwt_get_recent_packages', 'Packages created or updated in a date range. Update uses packages.updated_at.', [
                'start_date' => ['type' => 'string'],
                'end_date' => ['type' => 'string'],
                'page' => ['type' => 'integer', 'minimum' => 1],
                'per_page' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100],
            ], 'admin/packages', 'allow', ['start_date', 'end_date']),
            $this->tool('wwt_get_package_status_history', 'Current status and status-change date. WWT does not store earlier transitions.', [
                'waybill' => ['type' => 'string'],
            ], 'admin/packages', 'allow', ['waybill']),
            $this->tool('wwt_track_waybill', 'Authorized tracking for the token identity. This does not use the public tracking response.', [
                'waybill' => ['type' => 'string'],
            ], 'admin/packages', 'allow', ['waybill']),
            $this->tool('wwt_search_clients', 'Search customers by name, email, suite, or company. Staff only.', [
                'q' => ['type' => 'string'],
                'page' => ['type' => 'integer', 'minimum' => 1],
                'per_page' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100],
            ], 'admin/clients', 'deny', ['q']),
            $this->tool('wwt_get_client', 'One customer. A customer token always resolves to the authenticated account.', [
                'id' => ['type' => 'integer'],
                'suite' => ['type' => 'string'],
            ], 'admin/clients', 'self'),
            $this->tool('wwt_get_client_packages', 'Packages for one customer. A customer token always uses the authenticated account.', [
                'id' => ['type' => 'integer'],
                'suite' => ['type' => 'string'],
                'page' => ['type' => 'integer', 'minimum' => 1],
                'per_page' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100],
            ], 'admin/packages', 'self'),
            $this->tool('wwt_list_origins', 'Cargo origins with the address fields shown on the customer home page.', [], 'admin/origins', 'allow'),
            $this->tool('wwt_get_freight_rates', 'Stored freight rate rows. Customer tokens do not receive another customer\'s client-specific rows.', [
                'origin_id' => ['type' => 'integer'],
            ], 'admin/freight-rates', 'allow'),
            $this->tool('wwt_estimate_freight', 'Grand total using the package form formula: kilograms times the origin MAX(rate), plus 10 percent when insured.', [
                'origin_id' => ['type' => 'integer'],
                'kg' => ['type' => 'number'],
                'insured' => ['type' => 'boolean'],
            ], 'admin/freight-rates', 'allow', ['origin_id', 'kg']),
            $this->tool('wwt_get_package_statistics', 'Count, total kilograms, and total grand amount for the same filters as the admin package report.', [
                'origins' => ['type' => 'array', 'items' => ['type' => 'integer']],
                'branches' => ['type' => 'array', 'items' => ['type' => 'integer']],
                'statuses' => ['type' => 'array', 'items' => ['type' => 'string']],
                'types' => ['type' => 'array', 'items' => ['type' => 'string']],
                'clients' => ['type' => 'array', 'items' => ['type' => 'integer']],
                'start_date' => ['type' => 'string'],
                'end_date' => ['type' => 'string'],
            ], 'admin/reporting', 'allow'),
            $this->tool('wwt_get_weight_volume_summary', 'Sum of kilograms and cubic meters for the admin report filters. The admin screen totals kilograms and grand total; cubic meters are summed from those same rows.', [
                'origins' => ['type' => 'array', 'items' => ['type' => 'integer']],
                'branches' => ['type' => 'array', 'items' => ['type' => 'integer']],
                'statuses' => ['type' => 'array', 'items' => ['type' => 'string']],
                'types' => ['type' => 'array', 'items' => ['type' => 'string']],
                'clients' => ['type' => 'array', 'items' => ['type' => 'integer']],
                'start_date' => ['type' => 'string'],
                'end_date' => ['type' => 'string'],
            ], 'admin/reporting', 'allow'),
            $this->tool('wwt_get_payment_status_summary', 'Counts by the stored payment_status value. 1 is paid and 0 is unpaid.', [
                'start_date' => ['type' => 'string'],
                'end_date' => ['type' => 'string'],
            ], 'admin/packages', 'allow'),
            $this->tool('wwt_get_operational_report', 'Paginated rows from the admin package report query, plus the report kilogram and grand-total sums.', [
                'origins' => ['type' => 'array', 'items' => ['type' => 'integer']],
                'branches' => ['type' => 'array', 'items' => ['type' => 'integer']],
                'statuses' => ['type' => 'array', 'items' => ['type' => 'string']],
                'types' => ['type' => 'array', 'items' => ['type' => 'string']],
                'clients' => ['type' => 'array', 'items' => ['type' => 'integer']],
                'start_date' => ['type' => 'string'],
                'end_date' => ['type' => 'string'],
                'page' => ['type' => 'integer', 'minimum' => 1],
                'per_page' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100],
            ], 'admin/reporting', 'deny'),
            $this->tool('wwt_get_organization_profile', 'Company name and contact details. Tax identifiers are returned only to staff who can open organization settings.', [], 'admin/organization-create', 'allow'),
        ];
    }

    public function rule(string $name): ?array
    {
        foreach ($this->definitions() as $tool) {
            if ($tool['name'] === $name) {
                return $tool['_rule'];
            }
        }

        return null;
    }

    public function forActor(McpActor $actor, DesignationAuthorizer $authorizer): array
    {
        $tools = [];
        foreach ($this->definitions() as $tool) {
            if ($authorizer->allows($actor, $tool['name'])) {
                unset($tool['_rule']);
                $tools[] = $tool;
            }
        }

        return $tools;
    }

    private function tool(string $name, string $description, array $properties, string $staffSlug, string $client, array $required = []): array
    {
        return [
            'name' => $name,
            'description' => $description,
            'inputSchema' => [
                'type' => 'object',
                'properties' => $properties,
                'required' => $required,
                'additionalProperties' => false,
            ],
            'annotations' => [
                'readOnlyHint' => true,
                'destructiveHint' => false,
                'idempotentHint' => true,
                'openWorldHint' => false,
            ],
            '_rule' => [
                'staff_slug' => $staffSlug,
                'client' => $client,
            ],
        ];
    }
}
