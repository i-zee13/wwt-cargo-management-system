<?php

namespace App\Mcp;

class ContextCatalog
{
    public function list(): array
    {
        $resources = [];
        foreach ($this->documents() as $uri => $document) {
            $resources[] = [
                'uri' => $uri,
                'name' => $document['name'],
                'description' => $document['description'],
                'mimeType' => 'application/json',
            ];
        }

        return $resources;
    }

    public function read(string $uri): ?array
    {
        $documents = $this->documents();
        if (! isset($documents[$uri])) {
            return null;
        }

        return [
            'uri' => $uri,
            'mimeType' => 'application/json',
            'text' => json_encode($documents[$uri]['body'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ];
    }

    private function documents(): array
    {
        return [
            'wwt://context/overview' => [
                'name' => 'WWT overview',
                'description' => 'What the cargo system is and how Aide should use it.',
                'body' => [
                    'company' => 'World Wide Trading Group',
                    'system' => 'WWT Cargo Management is the system of record for packages, customers, origins, freight rates, and payments.',
                    'identifiers' => [
                        'waybill' => 'Primary package identifier. Public URLs replace spaces with hyphens. Lookups convert hyphens back to spaces.',
                        'suite' => 'Customer mailbox number stored on clients.suite and copied to packages.client_suite.',
                        'original_tracking' => 'Carrier tracking number recorded on the package. It is unique among packages.',
                    ],
                    'units' => [
                        'weight' => 'kg',
                        'volume' => 'cbm',
                    ],
                    'timezone' => config('app.timezone'),
                    'access' => 'Every MCP call is tied to one WWT staff user or one customer. A customer only sees packages with their client_id.',
                ],
            ],
            'wwt://context/packages' => [
                'name' => 'Packages',
                'description' => 'How a package is identified and which dates exist.',
                'body' => [
                    'creation' => 'A package without kilograms is stored as on-the-way. A new package with kilograms is stored as received. Adding kilograms to an on-the-way package changes it to received.',
                    'waybill_format' => 'Two origin letters, one type letter, date dmy, a space, then a four-digit sequence for that day.',
                    'types' => ['air', 'land', 'maritime'],
                    'dates' => [
                        'created_at' => 'When the package row was created.',
                        'updated_at' => 'Set when an existing package is saved again.',
                        'status_change_date' => 'Set when staff change the status from the package list. It is the current change only.',
                    ],
                    'history' => 'Earlier statuses are not stored.',
                ],
            ],
            'wwt://context/package-statuses' => [
                'name' => 'Package statuses',
                'description' => 'Status values used by the package screens.',
                'body' => [
                    'manual_values' => [
                        'received' => 'Received',
                        'embarked' => 'Embarked',
                        'in-progress' => 'In Transit',
                        'arrived' => 'Arrived',
                        'retired' => 'Retired',
                    ],
                    'automatic_value' => [
                        'on-the-way' => 'Set when a package is saved without a weight. It is not one of the status radios.',
                    ],
                    'transitions' => 'The status update action stores the submitted value and the current time. It does not enforce an ordered lifecycle.',
                    'customer_home' => [
                        'received_or_embarked' => 'Counted together as received on the customer home page.',
                        'in-progress' => 'Counted separately.',
                        'arrived' => 'Counted separately.',
                    ],
                ],
            ],
            'wwt://context/payment-statuses' => [
                'name' => 'Payment statuses',
                'description' => 'How package payment state is stored.',
                'body' => [
                    'field' => 'packages.payment_status',
                    'values' => [
                        '0' => 'Unpaid. Staff can toggle this from the package list.',
                        '1' => 'Paid. A successful Stripe checkout sets this to 1.',
                    ],
                    'currency' => 'USD. Stripe checkout uses usd, and the unpaid package list prefixes the grand total with $.',
                    'missing' => 'Null is missing data, not an unpaid package.',
                ],
            ],
            'wwt://context/origins-and-rates' => [
                'name' => 'Origins and freight rates',
                'description' => 'Where rates are stored and how the package form calculates a total.',
                'body' => [
                    'rate_row' => 'freight_rates stores branch_id, client_id, origin_id, rate, additional, and type.',
                    'package_form' => 'The origin dropdown uses MAX(freight_rates.rate) for that origin. Grand total is kilograms times that rate. If the package is insured, 10 percent of that product is rounded to two decimals and added.',
                    'additional' => 'The additional column is stored and shown on the rate list. The package form does not add it into the grand total.',
                    'insurance_values' => ['insured', 'not-insured'],
                ],
            ],
            'wwt://context/reporting' => [
                'name' => 'Reporting',
                'description' => 'Filters and totals used by the admin package report.',
                'body' => [
                    'filters' => ['origins', 'branches', 'statuses', 'types', 'clients', 'start_date', 'end_date'],
                    'date_field' => 'packages.created_at, inclusive, only when both dates are supplied.',
                    'totals' => 'The admin report sums kilograms and grand_total for the filtered rows. It does not display a cubic-meter total.',
                    'dashboard' => 'Admin home active clients are customers with created_at on or before the selected date. It is not a separate active flag.',
                ],
            ],
            'wwt://context/capabilities' => [
                'name' => 'MCP capabilities',
                'description' => 'What this MCP server will and will not do.',
                'body' => [
                    'read_only' => true,
                    'public_tracking' => 'GET /api/track/{waybill} remains the public contract and returns waybill, status, kg, cbm, and created_at only.',
                    'not_exposed' => [
                        'login logs',
                        'access-right configuration',
                        'email template content',
                        'translations',
                        'passwords',
                        'payment card data',
                        'maintenance routes',
                    ],
                ],
            ],
        ];
    }
}
