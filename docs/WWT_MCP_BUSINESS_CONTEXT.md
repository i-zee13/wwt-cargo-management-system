# WWT business context for Aide

World Wide Trading Group records cargo in this Laravel application. A customer registers, receives a suite number (`clients.suite`, prefixed from `config/brand.php` when staff do not type one), and packages are tied to that customer with `packages.client_id` and a copied `packages.client_suite`.

## Package lifecycle stored by the app

There is no enforced state machine. The screens and save action use these values:

| Stored value | Where it comes from | English label |
| --- | --- | --- |
| `on-the-way` | Package saved with no kilograms | On the way |
| `received` | New package saved with kilograms, or kilograms added to an `on-the-way` package. Also a status radio. | Received |
| `embarked` | Status radio | Embarked |
| `in-progress` | Status radio. Customer home and admin home treat this as in transit / processing. | In Transit |
| `arrived` | Status radio | Arrived |
| `retired` | Status radio. Admin home uses this as delivered for the selected day. | Retired |

`status_change_date` is overwritten when staff change status from the list. Older statuses are not kept. `wwt_get_package_status_history` returns that fact instead of a made-up timeline.

Waybills are built as two letters of the origin name, one letter of the type (`air`, `land`, or `maritime`), the date `dmy`, a space, and a four-digit count of packages created that day. Public URLs replace the space with hyphens. Lookups convert hyphens back to spaces.

Weight is kilograms (`packages.kg`). Volume is cubic meters (`packages.cbm`). A null weight is missing, not zero. The original carrier number is `packages.original_tracking`.

## Money

`packages.grand_total` is what the package form calculates and what reports sum. Stripe checkout uses currency `usd`. The unpaid-package screen prefixes the amount with `$`. MCP reports the currency as USD for that reason. There is no currency column on the package.

`payment_status`: `0` unpaid, `1` paid. Stripe success sets `1`. Any other stored value is reported as unknown.

The package form does this, in `public/js/custom/packages.js`:

- Rate = `MAX(freight_rates.rate)` for the selected origin.
- Total = kilograms × rate.
- If insurance is `insured`, add 10 percent of that product after rounding the percent to two decimals.
- If kilograms or the rate is missing or not positive, the total is 0.
- `freight_rates.additional` is stored and listed, and is not added by this formula.

A customer opening the package form sees that same MAX, including rates stored against another customer. The rate list tool hides another customer's `client_id` rows. The estimate tool follows the form, so the number can still be that maximum.

## Who sees what

Staff access follows designation slugs such as `admin/packages`, `admin/clients`, `admin/origins`, `admin/freight-rates`, `admin/reporting`, `admin/home`, and `admin/organization-create`. A super user still needs the slug to exist in `controllers`.

A customer token sees only rows with their `client_id`. Their home page counts are:

- received: status `received` or `embarked`
- in progress: `in-progress`
- arrived: `arrived`

Admin home, for a calendar date in `America/New_York`:

- active clients: `clients.created_at` on or before that date (there is no separate active flag)
- packages created that date
- `in-progress` packages whose `status_change_date` is that date
- `retired` packages whose `status_change_date` is that date, plus the sum of their kilograms

The admin report sums kilograms and grand total for the filtered rows. It does not show a cubic-meter total. The weight tool adds that sum from the same rows and also counts null kilograms so a missing weight is not reported as zero.

## Organization

The company profile is the first `organizations` row plus `organization_phone_no`. Customers receive name and contact fields. Registration number and VAT number are staff-only, and only for a designation that can open organization settings.
