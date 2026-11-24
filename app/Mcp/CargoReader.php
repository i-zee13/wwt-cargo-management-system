<?php

namespace App\Mcp;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class CargoReader
{
    public function run(McpActor $actor, string $tool, array $args): array
    {
        return match ($tool) {
            'wwt_get_cargo_overview' => $this->overview($actor, $args),
            'wwt_list_packages' => $this->listPackages($actor, $args),
            'wwt_get_package' => $this->packageDetail($actor, $args),
            'wwt_get_packages_by_status' => $this->byStatus($actor, $args),
            'wwt_get_recent_packages' => $this->recent($actor, $args),
            'wwt_get_package_status_history' => $this->statusHistory($actor, $args),
            'wwt_track_waybill' => $this->packageDetail($actor, $args),
            'wwt_search_clients' => $this->searchClients($actor, $args),
            'wwt_get_client' => $this->client($actor, $args),
            'wwt_get_client_packages' => $this->clientPackages($actor, $args),
            'wwt_list_origins' => $this->origins($args),
            'wwt_get_freight_rates' => $this->rates($actor, $args),
            'wwt_estimate_freight' => $this->estimate($args),
            'wwt_get_package_statistics' => $this->statistics($actor, $args),
            'wwt_get_weight_volume_summary' => $this->weightVolume($actor, $args),
            'wwt_get_payment_status_summary' => $this->payments($actor, $args),
            'wwt_get_operational_report' => $this->report($actor, $args),
            'wwt_get_organization_profile' => $this->organization($actor, $args),
            default => throw new McpToolException('unknown_tool', 'That tool is not available.'),
        };
    }

    private function overview(McpActor $actor, array $args): array
    {
        $this->only($args, ['date']);
        $date = $this->dateOrToday($args['date'] ?? null);

        if ($actor->isClient()) {
            $base = DB::table('packages')->where('client_id', $actor->id);

            return $this->meta('cargo_overview', [
                'scope' => 'customer',
                'packages_received' => (clone $base)->whereIn('status', ['received', 'embarked'])->count(),
                'packages_in_progress' => (clone $base)->where('status', 'in-progress')->count(),
                'packages_arrived' => (clone $base)->where('status', 'arrived')->count(),
                'status_counts' => $this->statusCounts(clone $base),
            ]);
        }

        return $this->meta('cargo_overview', [
            'scope' => 'staff',
            'date' => $date,
            'active_clients' => DB::table('clients')->whereDate('created_at', '<=', $date)->count(),
            'active_clients_meaning' => 'Customers with created_at on or before the date. This is the admin home count.',
            'packages_processing' => DB::table('packages')->where('status', 'in-progress')->whereDate('status_change_date', $date)->count(),
            'packages_created' => DB::table('packages')->whereDate('created_at', $date)->count(),
            'packages_retired' => DB::table('packages')->where('status', 'retired')->whereDate('status_change_date', $date)->count(),
            'retired_weight_kg' => $this->sumOrZero(DB::table('packages')->where('status', 'retired')->whereDate('status_change_date', $date), 'kg'),
            'status_counts' => $this->statusCounts(DB::table('packages')),
        ]);
    }

    private function listPackages(McpActor $actor, array $args): array
    {
        $this->only($args, ['status', 'payment_status', 'origin_id', 'suite', 'waybill', 'original_tracking', 'start_date', 'end_date', 'page', 'per_page']);
        [$page, $perPage] = $this->page($args);
        $query = $this->scopedPackages($actor);
        $this->applyListFilters($query, $actor, $args);

        return $this->pageResult('packages', $query->orderByDesc('packages.id'), $page, $perPage, function ($row) {
            return $this->listRow($row);
        });
    }

    private function byStatus(McpActor $actor, array $args): array
    {
        $this->only($args, ['status', 'page', 'per_page']);
        $status = $this->requiredString($args, 'status');

        return $this->listPackages($actor, [
            'status' => $status,
            'page' => $args['page'] ?? 1,
            'per_page' => $args['per_page'] ?? 25,
        ]);
    }

    private function recent(McpActor $actor, array $args): array
    {
        $this->only($args, ['start_date', 'end_date', 'page', 'per_page']);
        [$page, $perPage] = $this->page($args);
        [$start, $end] = $this->range($args);
        $query = $this->scopedPackages($actor)->where(function ($query) use ($start, $end) {
            $query->whereBetween('packages.created_at', [$start, $end])
                ->orWhereBetween('packages.updated_at', [$start, $end]);
        });

        return $this->pageResult('packages', $query->orderByDesc('packages.id'), $page, $perPage, function ($row) {
            return $this->listRow($row);
        });
    }

    private function packageDetail(McpActor $actor, array $args): array
    {
        $this->only($args, ['waybill']);
        $package = $this->findPackage($actor, $this->requiredString($args, 'waybill'));

        return $this->meta('package', [
            'waybill' => $package->waybill,
            'original_tracking' => $package->original_tracking,
            'client_suite' => $package->client_suite,
            'client_name' => $package->client_name,
            'origin' => $package->origin_name,
            'origin_id' => $package->origin_id === null ? null : (int) $package->origin_id,
            'type' => $package->type,
            'description' => $package->description,
            'comments' => $package->comments,
            'is_insured' => $package->is_insured,
            'weight_kg' => $this->num($package->kg),
            'volume_cbm' => $this->num($package->cbm),
            'grand_total' => $this->num($package->grand_total),
            'currency' => 'USD',
            'status' => $package->status,
            'status_change_date' => $package->status_change_date,
            'payment_status' => $package->payment_status === null ? null : (int) $package->payment_status,
            'payment_state' => $this->paymentState($package->payment_status),
            'created_at' => $package->created_at,
            'updated_at' => $package->updated_at,
        ]);
    }

    private function statusHistory(McpActor $actor, array $args): array
    {
        $this->only($args, ['waybill']);
        $package = $this->findPackage($actor, $this->requiredString($args, 'waybill'));

        return $this->meta('package_status', [
            'history_available' => false,
            'reason' => 'WWT stores only the current package status and status_change_date. Earlier transitions are not recorded.',
            'current' => [
                'waybill' => $package->waybill,
                'status' => $package->status,
                'status_change_date' => $package->status_change_date,
            ],
        ]);
    }

    private function searchClients(McpActor $actor, array $args): array
    {
        $this->only($args, ['q', 'page', 'per_page']);
        if ($actor->isClient()) {
            throw new McpToolException('forbidden', 'This identity cannot search customers.');
        }
        $q = $this->requiredString($args, 'q');
        [$page, $perPage] = $this->page($args);
        $like = '%'.addcslashes($q, '%_\\').'%';
        $query = $this->clientQuery()->where(function ($query) use ($like) {
            $query->where('clients.first_name', 'like', $like)
                ->orWhere('clients.last_name', 'like', $like)
                ->orWhere('clients.email', 'like', $like)
                ->orWhere('clients.suite', 'like', $like)
                ->orWhere('clients.company_name', 'like', $like);
        });

        return $this->pageResult('clients', $query->orderBy('clients.id'), $page, $perPage, fn ($row) => $this->clientRow($row));
    }

    private function client(McpActor $actor, array $args): array
    {
        $this->only($args, ['id', 'suite']);
        $query = $this->clientQuery();
        if ($actor->isClient()) {
            $query->where('clients.id', $actor->id);
        } elseif (isset($args['id'])) {
            $query->where('clients.id', $this->integer($args['id'], 'id'));
        } elseif (isset($args['suite'])) {
            $query->where('clients.suite', $this->requiredString(['suite' => $args['suite']], 'suite'));
        } else {
            throw new McpToolException('invalid_params', 'Provide a customer id or suite.');
        }

        $row = $query->first();
        if (! $row) {
            throw new McpToolException('not_found', 'No customer matches that request.');
        }

        return $this->meta('client', $this->clientRow($row));
    }

    private function clientPackages(McpActor $actor, array $args): array
    {
        $this->only($args, ['id', 'suite', 'page', 'per_page']);
        [$page, $perPage] = $this->page($args);
        $query = $this->scopedPackages($actor);
        if ($actor->isClient()) {
            $query->where('packages.client_id', $actor->id);
        } elseif (isset($args['id'])) {
            $query->where('packages.client_id', $this->integer($args['id'], 'id'));
        } elseif (isset($args['suite'])) {
            $query->where('packages.client_suite', $this->requiredString(['suite' => $args['suite']], 'suite'));
        } else {
            throw new McpToolException('invalid_params', 'Provide a customer id or suite.');
        }

        return $this->pageResult('packages', $query->orderByDesc('packages.id'), $page, $perPage, function ($row) {
            return $this->listRow($row);
        });
    }

    private function origins(array $args): array
    {
        $this->only($args, []);
        $rows = DB::table('origins')
            ->leftJoin('countries', 'countries.id', '=', 'origins.country_id')
            ->leftJoin('states', 'states.id', '=', 'origins.state_id')
            ->leftJoin('cities', 'cities.id', '=', 'origins.city_id')
            ->select(
                'origins.id',
                'origins.origin_name',
                'origins.address',
                'origins.email',
                'origins.zip_code',
                'origins.phone',
                'countries.name as country_name',
                'countries.iso',
                'states.name as state_name',
                'cities.name as city_name'
            )
            ->orderBy('origins.id')
            ->get()
            ->map(fn ($row) => [
                'id' => (int) $row->id,
                'origin_name' => $row->origin_name,
                'address' => $row->address,
                'email' => $row->email,
                'zip_code' => $row->zip_code,
                'phone' => $row->phone,
                'country_name' => $row->country_name,
                'iso' => $row->iso,
                'state_name' => $row->state_name,
                'city_name' => $row->city_name,
            ])->all();

        return $this->meta('origins', $rows, count($rows), count($rows));
    }

    private function rates(McpActor $actor, array $args): array
    {
        $this->only($args, ['origin_id']);
        $query = DB::table('freight_rates')
            ->leftJoin('branches', 'freight_rates.branch_id', '=', 'branches.id')
            ->leftJoin('origins', 'freight_rates.origin_id', '=', 'origins.id')
            ->select(
                'freight_rates.id',
                'freight_rates.origin_id',
                'origins.origin_name',
                'freight_rates.branch_id',
                'branches.branch as branch_name',
                'freight_rates.client_id',
                'freight_rates.rate',
                'freight_rates.additional',
                'freight_rates.type'
            );
        if (isset($args['origin_id'])) {
            $query->where('freight_rates.origin_id', $this->integer($args['origin_id'], 'origin_id'));
        }
        if ($actor->isClient()) {
            $query->where(function ($query) use ($actor) {
                $query->whereNull('freight_rates.client_id')->orWhere('freight_rates.client_id', $actor->id);
            });
        }
        $rows = $query->orderBy('freight_rates.id')->get()->map(fn ($row) => [
            'id' => (int) $row->id,
            'origin_id' => $row->origin_id === null ? null : (int) $row->origin_id,
            'origin_name' => $row->origin_name,
            'branch_id' => $row->branch_id === null ? null : (int) $row->branch_id,
            'branch_name' => $row->branch_name,
            'client_id' => $row->client_id === null ? null : (int) $row->client_id,
            'rate' => $this->num($row->rate),
            'additional' => $this->num($row->additional),
            'additional_used_in_grand_total' => false,
            'type' => $row->type,
        ])->all();

        return $this->meta('freight_rates', $rows, count($rows), count($rows));
    }

    private function estimate(array $args): array
    {
        $this->only($args, ['origin_id', 'kg', 'insured']);
        $originId = $this->integer($args['origin_id'] ?? null, 'origin_id');
        if (! isset($args['kg']) || ! is_numeric($args['kg'])) {
            throw new McpToolException('invalid_params', 'kg must be a number.');
        }
        $kg = (float) $args['kg'];
        $insured = (bool) ($args['insured'] ?? false);
        $rate = DB::table('freight_rates')->where('origin_id', $originId)->max('rate');
        $rate = $rate === null ? null : (float) $rate;
        $base = ($rate === null || $kg <= 0 || $rate <= 0) ? 0.0 : $kg * $rate;
        $insurance = 0.0;
        if ($insured && $base > 0) {
            $insurance = round($base * 0.10, 2);
            $base += $insurance;
        }
        $grand = round($base, 2);

        return $this->meta('freight_estimate', [
            'origin_id' => $originId,
            'weight_kg' => $kg,
            'rate_per_kg' => $rate,
            'rate_selection' => 'MAX(freight_rates.rate) for the origin, the same value the package form puts on the origin option.',
            'insured' => $insured,
            'insurance_amount' => $insurance,
            'insurance_rule' => 'Ten percent of kilograms times rate, rounded to two decimals, then added. Source: public/js/custom/packages.js calculateGrandTotal.',
            'additional_column_applied' => false,
            'currency' => 'USD',
            'grand_total' => $grand,
        ]);
    }

    private function statistics(McpActor $actor, array $args): array
    {
        $query = $this->reportBase($actor, $args);
        $count = (clone $query)->count();

        return $this->meta('package_statistics', [
            'package_count' => $count,
            'total_kg' => $this->sumOrNull($query, 'packages.kg', $count),
            'total_cbm' => $this->sumOrNull($query, 'packages.cbm', $count),
            'total_grand_total' => $this->sumOrNull($query, 'packages.grand_total', $count),
            'currency' => 'USD',
            'admin_report_totals' => ['total_kg', 'total_grand_total'],
        ]);
    }

    private function weightVolume(McpActor $actor, array $args): array
    {
        $query = $this->reportBase($actor, $args);
        $count = (clone $query)->count();

        return $this->meta('weight_volume', [
            'package_count' => $count,
            'total_kg' => $this->sumOrNull($query, 'packages.kg', $count),
            'total_cbm' => $this->sumOrNull($query, 'packages.cbm', $count),
            'null_kg_count' => (clone $query)->whereNull('packages.kg')->count(),
            'null_cbm_count' => (clone $query)->whereNull('packages.cbm')->count(),
            'units' => ['weight' => 'kg', 'volume' => 'cbm'],
        ]);
    }

    private function payments(McpActor $actor, array $args): array
    {
        $this->only($args, ['start_date', 'end_date']);
        $query = $this->scopedPackages($actor);
        if (isset($args['start_date']) || isset($args['end_date'])) {
            [$start, $end] = $this->range($args);
            $query->whereBetween('packages.created_at', [$start, $end]);
        }
        $rows = (clone $query)
            ->select('packages.payment_status', DB::raw('count(*) as aggregate'))
            ->groupBy('packages.payment_status')
            ->get();
        $buckets = ['paid' => 0, 'unpaid' => 0, 'unknown' => 0];
        $raw = [];
        foreach ($rows as $row) {
            $state = $this->paymentState($row->payment_status);
            $key = $row->payment_status === null ? 'null' : (string) $row->payment_status;
            $raw[$key] = (int) $row->aggregate;
            $buckets[$state === null ? 'unknown' : $state] += (int) $row->aggregate;
        }

        return $this->meta('payment_status', [
            'paid' => $buckets['paid'],
            'unpaid' => $buckets['unpaid'],
            'unknown' => $buckets['unknown'],
            'raw_counts' => $raw,
            'package_count' => (clone $query)->count(),
        ]);
    }

    private function report(McpActor $actor, array $args): array
    {
        if ($actor->isClient()) {
            throw new McpToolException('forbidden', 'This identity cannot open the operational report.');
        }
        [$page, $perPage] = $this->page($args);
        $query = $this->reportBase($actor, $args);
        $count = (clone $query)->count();
        $rows = (clone $query)
            ->select(
                'packages.id as guide',
                'packages.waybill',
                'origins.origin_name as origin',
                'packages.description',
                'packages.type',
                'packages.original_tracking as tracking',
                'packages.created_at as date',
                'packages.kg',
                'packages.cbm',
                'clients.first_name as client',
                'clients.last_name as client_last',
                'clients.suite',
                'branches.branch',
                'packages.status',
                'packages.grand_total as total',
                'packages.status_change_date as delivered'
            )
            ->orderBy('packages.id')
            ->forPage($page, $perPage)
            ->get()
            ->map(fn ($row) => [
                'guide' => (int) $row->guide,
                'waybill' => $row->waybill,
                'origin' => $row->origin,
                'description' => $row->description,
                'type' => $row->type,
                'tracking' => $row->tracking,
                'date' => $row->date,
                'kg' => $this->num($row->kg),
                'cbm' => $this->num($row->cbm),
                'client' => $row->client,
                'client_last' => $row->client_last,
                'suite' => $row->suite,
                'branch' => $row->branch,
                'status' => $row->status,
                'total' => $this->num($row->total),
                'delivered' => $row->delivered,
            ])->all();

        $payload = $this->pageEnvelope('operational_report', $rows, $count, $page, $perPage);
        $payload['total_kg'] = $this->sumOrNull($query, 'packages.kg', $count);
        $payload['total_grand_total'] = $this->sumOrNull($query, 'packages.grand_total', $count);
        $payload['currency'] = 'USD';

        return $payload;
    }

    private function organization(McpActor $actor, array $args): array
    {
        $this->only($args, []);
        $org = DB::table('organizations')->first();
        $phones = DB::table('organization_phone_no')->select('type', 'phone_number')->get()->map(fn ($row) => [
            'type' => $row->type,
            'phone_number' => $row->phone_number,
        ])->all();
        $data = [
            'name' => $org->name ?? config('brand.name'),
            'phone' => $org->phone ?? null,
            'email' => $org->email ?? null,
            'address' => $org->address ?? null,
            'postal_code' => $org->postal_code ?? null,
            'facebook_link' => $org->facebook_link ?? null,
            'instagram_link' => $org->instagram_link ?? null,
            'linkedin_link' => $org->linkedin_link ?? null,
            'youtube_link' => $org->youtube_link ?? null,
            'twitter_link' => $org->twitter_link ?? null,
            'phones' => $phones,
        ];
        if (! $actor->isClient()) {
            $data['registration_number'] = $org->registration_number ?? null;
            $data['vat_number'] = $org->vat_number ?? null;
        }

        return $this->meta('organization', $data);
    }

    private function reportBase(McpActor $actor, array $args): Builder
    {
        $this->only($args, ['origins', 'branches', 'statuses', 'types', 'clients', 'start_date', 'end_date', 'page', 'per_page']);
        $query = DB::table('packages')
            ->leftJoin('clients', 'packages.client_id', '=', 'clients.id')
            ->leftJoin('origins', 'packages.origin_id', '=', 'origins.id')
            ->leftJoin('branches', 'clients.branch_id', '=', 'branches.id');
        if ($actor->isClient()) {
            $query->where('packages.client_id', $actor->id);
        } elseif (! empty($args['clients'])) {
            $query->whereIn('packages.client_id', $this->intList($args['clients'], 'clients'));
        }
        if (! empty($args['origins'])) {
            $query->whereIn('packages.origin_id', $this->intList($args['origins'], 'origins'));
        }
        if (! empty($args['branches'])) {
            $query->whereIn('clients.branch_id', $this->intList($args['branches'], 'branches'));
        }
        if (! empty($args['statuses'])) {
            $query->whereIn('packages.status', $this->stringList($args['statuses'], 'statuses'));
        }
        if (! empty($args['types'])) {
            $query->whereIn('packages.type', $this->stringList($args['types'], 'types'));
        }
        if (! empty($args['start_date']) || ! empty($args['end_date'])) {
            [$start, $end] = $this->range($args);
            $query->whereBetween('packages.created_at', [$start, $end]);
        }

        return $query;
    }

    private function scopedPackages(McpActor $actor): Builder
    {
        $query = DB::table('packages')
            ->leftJoin('origins', 'packages.origin_id', '=', 'origins.id')
            ->select(
                'packages.id',
                'packages.waybill',
                'packages.original_tracking',
                'packages.client_suite',
                'packages.client_name',
                'packages.client_id',
                'packages.origin_id',
                'origins.origin_name',
                'packages.type',
                'packages.description',
                'packages.comments',
                'packages.is_insured',
                'packages.created_at',
                'packages.updated_at',
                'packages.kg',
                'packages.cbm',
                'packages.grand_total',
                'packages.status',
                'packages.status_change_date',
                'packages.payment_status'
            );
        if ($actor->isClient()) {
            $query->where('packages.client_id', $actor->id);
        }

        return $query;
    }

    private function applyListFilters(Builder $query, McpActor $actor, array $args): void
    {
        if (! empty($args['status'])) {
            $query->where('packages.status', $this->requiredString(['status' => $args['status']], 'status'));
        }
        if (array_key_exists('payment_status', $args)) {
            $status = $args['payment_status'];
            if (! in_array($status, [0, 1], true)) {
                throw new McpToolException('invalid_params', 'payment_status must be 0 or 1.');
            }
            $query->where('packages.payment_status', $status);
        }
        if (isset($args['origin_id'])) {
            $query->where('packages.origin_id', $this->integer($args['origin_id'], 'origin_id'));
        }
        if (! $actor->isClient() && isset($args['suite'])) {
            $query->where('packages.client_suite', $this->requiredString(['suite' => $args['suite']], 'suite'));
        }
        if (! empty($args['waybill'])) {
            $query->where('packages.waybill', $this->normalizeWaybill($this->requiredString(['waybill' => $args['waybill']], 'waybill')));
        }
        if (! empty($args['original_tracking'])) {
            $query->where('packages.original_tracking', $this->requiredString(['original_tracking' => $args['original_tracking']], 'original_tracking'));
        }
        if (! empty($args['start_date']) || ! empty($args['end_date'])) {
            [$start, $end] = $this->range($args);
            $query->whereBetween('packages.created_at', [$start, $end]);
        }
    }

    private function findPackage(McpActor $actor, string $waybill): object
    {
        $package = $this->scopedPackages($actor)
            ->where('packages.waybill', $this->normalizeWaybill($waybill))
            ->first();
        if (! $package) {
            throw new McpToolException('not_found', 'No package matches that waybill.');
        }

        return $package;
    }

    private function clientQuery(): Builder
    {
        return DB::table('clients')
            ->leftJoin('branches', 'clients.branch_id', '=', 'branches.id')
            ->select(
                'clients.id',
                'clients.first_name',
                'clients.last_name',
                'clients.company_name',
                'clients.email',
                'clients.suite',
                'clients.phone',
                'clients.client_type',
                'clients.email_verified_at',
                'clients.created_at',
                'branches.branch as branch_name'
            );
    }

    private function clientRow(object $row): array
    {
        return [
            'id' => (int) $row->id,
            'first_name' => $row->first_name,
            'last_name' => $row->last_name,
            'company_name' => $row->company_name,
            'email' => $row->email,
            'suite' => $row->suite,
            'phone' => $row->phone,
            'client_type' => $row->client_type,
            'branch_name' => $row->branch_name,
            'email_verified' => $row->email_verified_at !== null,
            'created_at' => $row->created_at,
        ];
    }

    private function listRow(object $row): array
    {
        return [
            'waybill' => $row->waybill,
            'original_tracking' => $row->original_tracking,
            'client_suite' => $row->client_suite,
            'client_name' => $row->client_name,
            'origin' => $row->origin_name,
            'weight_kg' => $this->num($row->kg),
            'volume_cbm' => $this->num($row->cbm),
            'status' => $row->status,
            'payment_status' => $row->payment_status === null ? null : (int) $row->payment_status,
            'payment_state' => $this->paymentState($row->payment_status),
            'grand_total' => $this->num($row->grand_total),
            'currency' => 'USD',
            'created_at' => $row->created_at,
        ];
    }

    private function statusCounts(Builder $query): array
    {
        $counts = [];
        foreach ($query->select('status', DB::raw('count(*) as aggregate'))->groupBy('status')->get() as $row) {
            $counts[(string) $row->status] = (int) $row->aggregate;
        }

        return $counts;
    }

    private function pageResult(string $entity, Builder $query, int $page, int $perPage, callable $map): array
    {
        $total = (clone $query)->count();
        $rows = $query->forPage($page, $perPage)->get()->map($map)->all();

        return $this->pageEnvelope($entity, $rows, $total, $page, $perPage);
    }

    private function pageEnvelope(string $entity, array $data, int $total, int $page, int $perPage): array
    {
        $payload = $this->meta($entity, $data, count($data), $total);
        $payload['page'] = $page;
        $payload['per_page'] = $perPage;
        $payload['has_more'] = ($page * $perPage) < $total;

        return $payload;
    }

    private function meta(string $entity, mixed $data, ?int $count = null, ?int $total = null): array
    {
        $isList = is_array($data) && array_is_list($data);

        return [
            'source' => 'wwt_cargo',
            'entity' => $entity,
            'data' => $data,
            'count' => $count ?? ($isList ? count($data) : 1),
            'total_matching' => $total ?? ($isList ? count($data) : 1),
            'retrieved_at' => Carbon::now('UTC')->toIso8601String(),
            'timezone' => (string) config('app.timezone'),
            'has_more' => false,
        ];
    }

    private function paymentState(mixed $status): ?string
    {
        if ($status === null || $status === '') {
            return null;
        }

        return match ((string) $status) {
            '1' => 'paid',
            '0' => 'unpaid',
            default => 'unknown',
        };
    }

    private function sumOrNull(Builder $query, string $column, int $count): ?float
    {
        if ($count === 0) {
            return null;
        }
        $value = (clone $query)->sum($column);

        return $value === null ? null : (float) $value;
    }

    private function sumOrZero(Builder $query, string $column): float
    {
        return (float) ($query->sum($column) ?? 0);
    }

    private function num(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (float) $value;
    }

    private function normalizeWaybill(string $waybill): string
    {
        return str_replace('-', ' ', trim($waybill));
    }

    private function only(array $args, array $allowed): void
    {
        $unknown = array_diff(array_keys($args), $allowed);
        if ($unknown !== []) {
            throw new McpToolException('invalid_params', 'Unknown parameter: '.implode(', ', $unknown));
        }
    }

    private function page(array $args): array
    {
        $page = isset($args['page']) ? $this->integer($args['page'], 'page') : 1;
        $perPage = isset($args['per_page']) ? $this->integer($args['per_page'], 'per_page') : 25;
        if ($page < 1) {
            throw new McpToolException('invalid_params', 'page must be 1 or greater.');
        }
        if ($perPage < 1 || $perPage > 100) {
            throw new McpToolException('invalid_params', 'per_page must be from 1 to 100.');
        }

        return [$page, $perPage];
    }

    private function range(array $args): array
    {
        if (empty($args['start_date']) || empty($args['end_date'])) {
            throw new McpToolException('invalid_params', 'Provide both start_date and end_date as YYYY-MM-DD.');
        }
        $start = $this->day($args['start_date'])->startOfDay();
        $end = $this->day($args['end_date'])->endOfDay();
        if ($end->lt($start)) {
            throw new McpToolException('invalid_params', 'end_date is before start_date.');
        }

        return [$start->toDateTimeString(), $end->toDateTimeString()];
    }

    private function dateOrToday(mixed $date): string
    {
        if ($date === null || $date === '') {
            return Carbon::today()->toDateString();
        }

        return $this->day($date)->toDateString();
    }

    private function day(mixed $value): Carbon
    {
        if (! is_string($value) || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            throw new McpToolException('invalid_params', 'Dates must use YYYY-MM-DD.');
        }
        try {
            $date = Carbon::createFromFormat('Y-m-d', $value);
        } catch (\Throwable) {
            throw new McpToolException('invalid_params', 'Dates must use YYYY-MM-DD.');
        }
        if ($date === false || $date->format('Y-m-d') !== $value) {
            throw new McpToolException('invalid_params', 'Dates must use YYYY-MM-DD.');
        }

        return $date;
    }

    private function requiredString(array $args, string $key): string
    {
        $value = $args[$key] ?? null;
        if (! is_string($value) || trim($value) === '' || mb_strlen($value) > 120) {
            throw new McpToolException('invalid_params', $key.' must be a non-empty string.');
        }

        return trim($value);
    }

    private function integer(mixed $value, string $key): int
    {
        if (! is_int($value) && ! (is_string($value) && preg_match('/^\d+$/', $value))) {
            throw new McpToolException('invalid_params', $key.' must be an integer.');
        }
        $int = (int) $value;
        if ($int < 1) {
            throw new McpToolException('invalid_params', $key.' must be 1 or greater.');
        }

        return $int;
    }

    private function intList(mixed $values, string $key): array
    {
        if (! is_array($values) || $values === []) {
            throw new McpToolException('invalid_params', $key.' must be a list of integers.');
        }

        return array_map(fn ($value) => $this->integer($value, $key), $values);
    }

    private function stringList(mixed $values, string $key): array
    {
        if (! is_array($values) || $values === []) {
            throw new McpToolException('invalid_params', $key.' must be a list of strings.');
        }
        $out = [];
        foreach ($values as $value) {
            if (! is_string($value) || trim($value) === '' || mb_strlen($value) > 40) {
                throw new McpToolException('invalid_params', $key.' must contain short strings.');
            }
            $out[] = trim($value);
        }

        return $out;
    }
}
