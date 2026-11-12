# WWT MCP tool catalog

All tools are read-only. Responses include `source: wwt_cargo`, `retrieved_at` in UTC, and `timezone` (`America/New_York`). List tools return `count` for the page and `total_matching` for the full filter. `count` is not the business total when `has_more` is true.

Staff need the designation slug below, unless `users.super = 1` and that slug exists in `controllers`. Customers are limited to their own `client_id`.

| Tool | Staff slug | Customer | Source |
| --- | --- | --- | --- |
| `wwt_get_cargo_overview` | `admin/home` | Own home counts | `HomeController::index`, `HomeController::customer_index` |
| `wwt_list_packages` | `admin/packages` | Own rows | `packages` + `origins`, filters from the package list and report |
| `wwt_get_package` | `admin/packages` | Own waybill | `PackageController` waybill normalization |
| `wwt_get_packages_by_status` | `admin/packages` | Own rows | Same list, required `status` |
| `wwt_get_recent_packages` | `admin/packages` | Own rows | `created_at` or `updated_at` in the range |
| `wwt_get_package_status_history` | `admin/packages` | Own waybill | Current `status` and `status_change_date` only |
| `wwt_track_waybill` | `admin/packages` | Own waybill | Same authorized package fields as `wwt_get_package` |
| `wwt_search_clients` | `admin/clients` | No | `clients` name, email, suite, company |
| `wwt_get_client` | `admin/clients` | Self only | `ClientsController` fields, without password or document number |
| `wwt_get_client_packages` | `admin/packages` | Self only | Packages for that customer |
| `wwt_list_origins` | `admin/origins` | Yes | Origin query used on the customer home page |
| `wwt_get_freight_rates` | `admin/freight-rates` | Own and shared rows | `FreightRateController::getRates` |
| `wwt_estimate_freight` | `admin/freight-rates` | Yes | `public/js/custom/packages.js` `calculateGrandTotal` |
| `wwt_get_package_statistics` | `admin/reporting` | Own rows | `ReportingController::generate_report` totals |
| `wwt_get_weight_volume_summary` | `admin/reporting` | Own rows | Same filters, plus cubic meters |
| `wwt_get_payment_status_summary` | `admin/packages` | Own rows | `packages.payment_status` |
| `wwt_get_operational_report` | `admin/reporting` | No | `ReportingController::generate_report` columns, paginated |
| `wwt_get_organization_profile` | `admin/organization-create` | Contact fields only | `organizations`, `organization_phone_no` |

## Inputs that matter

- Dates are `YYYY-MM-DD`. A range needs both `start_date` and `end_date`.
- `page` starts at 1. `per_page` is 1–100 and defaults to 25.
- `payment_status` is `0` or `1`.
- `waybill` may contain hyphens. They are converted to spaces before the query.
- Unknown argument names are rejected. Do not send `workspace_id`.
- Customer arguments `id` and `suite` on client tools do not change whose data is returned.

## Example questions

- How many packages were created today, and how many were retired today?
- Which unpaid packages are in transit?
- What is the current status of waybill `MI-A-120011-0001`?
- What would a 10 kg insured shipment from this origin cost, using the package form?
- What is the total kilograms and grand total for received packages this month?

## Omitted on purpose

No tool reads login logs, access-right setup, email template bodies, or translations. No tool writes packages, payments, or customers. `wwt_get_package_status_history` does not invent transitions. `wwt_track_waybill` is not the public tracking API.
