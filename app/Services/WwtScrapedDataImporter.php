<?php

namespace App\Services;

use App\Models\Branches;
use App\Models\ClientsModel;
use App\Models\DocumentTypes;
use App\Models\PackageModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class WwtScrapedDataImporter
{
    private array $stats = [
        'customers_processed' => 0,
        'customers_created' => 0,
        'customers_updated' => 0,
        'customers_skipped' => 0,
        'packages_created' => 0,
        'packages_skipped' => 0,
        'errors' => [],
    ];

    private array $customerLookup = [];

    private array $branchCache = [];

    private array $documentTypeCache = [];

    public function __construct(
        private readonly bool $dryRun = false,
        private readonly bool $onlyWithPackages = true,
        private readonly bool $skipExistingPackages = true,
        private readonly bool $updateExistingClients = true,
        private readonly int $createdBy = 1,
        private readonly string $defaultPassword = 'WwtImport@2026',
    ) {
    }

    public function importClientsOnly(string $customersPath): array
    {
        $customersPayload = $this->readJson($customersPath);
        $customers = $customersPayload['customers'] ?? $customersPayload['rows'] ?? [];

        if (! is_array($customers)) {
            throw new RuntimeException('Invalid customers JSON structure.');
        }

        foreach ($customers as $customer) {
            $customerId = trim((string) ($customer['customer_id'] ?? ''));
            $this->stats['customers_processed']++;

            try {
                $suite = trim((string) ($customer['casilla'] ?? ''));
                $existing = $suite !== '' ? ClientsModel::where('suite', $suite)->first() : null;

                if (! $existing) {
                    $email = strtolower(trim((string) ($customer['email'] ?? '')));
                    if ($email !== '') {
                        $existing = ClientsModel::whereRaw('LOWER(email) = ?', [$email])->first();
                    }
                }

                if ($existing && ! $this->updateExistingClients) {
                    $this->stats['customers_skipped']++;
                    continue;
                }

                $this->upsertClient($customer);
            } catch (\Throwable $exception) {
                $label = $customerId !== '' ? $customerId : ($customer['casilla'] ?? 'unknown');
                $this->recordError("Customer {$label}: {$exception->getMessage()}");
                $this->stats['customers_skipped']++;
            }
        }

        return $this->stats;
    }

    public function import(string $packagesPath, string $customersPath): array
    {
        $packagesPayload = $this->readJson($packagesPath);
        $customersPayload = $this->readJson($customersPath);

        $packages = $packagesPayload['packages'] ?? $packagesPayload['rows'] ?? [];
        $customers = $customersPayload['customers'] ?? $customersPayload['rows'] ?? [];

        if (! is_array($packages) || ! is_array($customers)) {
            throw new RuntimeException('Invalid scraper JSON structure.');
        }

        $this->customerLookup = $this->indexCustomers($customers);
        $packagesByCustomer = $this->groupPackagesByCustomer($packages);

        $customerIds = $this->onlyWithPackages
            ? array_keys($packagesByCustomer)
            : array_keys($this->customerLookup);

        sort($customerIds, SORT_NUMERIC);

        foreach ($customerIds as $customerId) {
            $this->importCustomerWithPackages(
                $customerId,
                $packagesByCustomer[$customerId] ?? []
            );
        }

        return $this->stats;
    }

    public function getStats(): array
    {
        return $this->stats;
    }

    private function importCustomerWithPackages(string $customerId, array $packages): void
    {
        $this->stats['customers_processed']++;

        $customer = $this->customerLookup[$customerId] ?? null;
        if (! $customer) {
            $this->recordError("Customer {$customerId} not found in customers JSON.");
            $this->stats['customers_skipped']++;
            return;
        }

        if ($this->onlyWithPackages && $packages === []) {
            $this->stats['customers_skipped']++;
            return;
        }

        try {
            $client = $this->upsertClient($customer);
        } catch (\Throwable $exception) {
            $this->recordError("Customer {$customerId} ({$customer['casilla']}): {$exception->getMessage()}");
            $this->stats['customers_skipped']++;
            return;
        }

        foreach ($packages as $packageRow) {
            try {
                $this->importPackage($client, $packageRow);
            } catch (\Throwable $exception) {
                $tracking = $packageRow['original_tracking'] ?? 'unknown';
                $this->recordError("Package {$tracking} for {$customer['casilla']}: {$exception->getMessage()}");
            }
        }
    }

    private function upsertClient(array $customer): ClientsModel
    {
        $suite = trim((string) ($customer['casilla'] ?? ''));
        if ($suite === '') {
            throw new RuntimeException('Missing casilla/suite.');
        }

        $existing = ClientsModel::where('suite', $suite)->first();
        if (! $existing) {
            $email = strtolower(trim((string) ($customer['email'] ?? '')));
            if ($email !== '') {
                $existing = ClientsModel::whereRaw('LOWER(email) = ?', [$email])->first();
            }
        }

        if ($existing && ! $this->updateExistingClients) {
            return $existing;
        }

        [$firstName, $lastName] = $this->splitName((string) ($customer['nombre'] ?? ''));
        $location = $this->resolveLocation($customer);

        if ($this->dryRun) {
            $client = $existing ?? new ClientsModel();
            $client->suite = $suite;
            $client->id = $existing?->id ?? 0;

            if ($existing) {
                $this->stats['customers_updated']++;
            } else {
                $this->stats['customers_created']++;
            }

            return $client;
        }

        $client = $existing ?? new ClientsModel();
        $isNew = ! $existing;

        $client->suite = $suite;
        $client->first_name = $firstName;
        $client->last_name = $lastName;
        $client->email = trim((string) ($customer['email'] ?? ''));
        $client->phone = trim((string) ($customer['telefono'] ?? ''));
        $client->document_number = trim((string) ($customer['documento'] ?? ''));
        $client->address = $location['address'];
        $client->postal_code = $location['postal_code'];
        $client->locality = $location['locality'];
        $client->client_type = 'person';
        $client->country_id = $location['country_id'];
        $client->state_id = $location['state_id'];
        $client->branch_id = $location['branch_id'];
        $client->document_type_id = $location['document_type_id'];

        if ($isNew || empty($client->password)) {
            $client->password = Hash::make($this->defaultPassword);
        }

        if ($isNew) {
            $client->created_at = Carbon::now();
            $client->created_by = $this->createdBy;
            $client->email_verified_at = Carbon::now();
        } else {
            $client->updated_at = Carbon::now();
            $client->updated_by = $this->createdBy;
        }

        $client->save();

        if ($isNew) {
            $this->stats['customers_created']++;
        } else {
            $this->stats['customers_updated']++;
        }

        return $client;
    }

    private function importPackage(ClientsModel $client, array $packageRow): void
    {
        $tracking = trim((string) ($packageRow['original_tracking'] ?? ''));
        if ($tracking === '') {
            throw new RuntimeException('Missing original_tracking.');
        }

        if ($this->skipExistingPackages) {
            $exists = PackageModel::where('original_tracking', $tracking)->exists();
            if ($exists) {
                $this->stats['packages_skipped']++;
                return;
            }
        }

        $waybill = trim((string) ($packageRow['waybill'] ?? ''));
        [$type, $originId] = $this->resolveTypeAndOrigin($waybill);
        $createdAt = $this->parsePackageDate((string) ($packageRow['date'] ?? ''));
        $clientName = trim($client->first_name.' '.$client->last_name);

        if ($this->dryRun) {
            $this->stats['packages_created']++;
            return;
        }

        $package = new PackageModel();
        $package->original_tracking = $tracking;
        $package->waybill = $waybill !== '' ? $waybill : null;
        $package->type = $type;
        $package->origin_id = $originId;
        $package->client_id = $client->id;
        $package->client_suite = $client->suite;
        $package->client_name = $clientName;
        $package->description = 'Imported from WWT scraper';
        $package->kg = (float) ($packageRow['kg'] ?? 0);
        $package->cbm = 0.0;
        $package->grand_total = (float) ($packageRow['grand_total'] ?? 0);
        $package->is_insured = 'not-insured';
        $package->comments = null;
        $package->status = $this->mapStatus((string) ($packageRow['current_status'] ?? ''));
        $package->status_change_date = $createdAt;
        $package->invoice_image = null;
        $package->other_document = null;
        $package->payment_status = 0;
        $package->created_at = $createdAt;
        $package->updated_at = $createdAt;
        $package->created_by = $this->createdBy;
        $package->updated_by = $this->createdBy;

        $package->save();
        $this->stats['packages_created']++;
    }

    private function indexCustomers(array $customers): array
    {
        $lookup = [];
        foreach ($customers as $customer) {
            $id = trim((string) ($customer['customer_id'] ?? ''));
            if ($id !== '') {
                $lookup[$id] = $customer;
            }
        }

        return $lookup;
    }

    private function groupPackagesByCustomer(array $packages): array
    {
        $grouped = [];
        foreach ($packages as $package) {
            $customerId = trim((string) ($package['customer_id'] ?? ''));
            if ($customerId === '') {
                continue;
            }
            $grouped[$customerId][] = $package;
        }

        return $grouped;
    }

    private function splitName(string $fullName): array
    {
        $fullName = preg_replace('/\s+/', ' ', trim($fullName)) ?: 'Imported Client';
        $parts = explode(' ', $fullName);

        if (count($parts) === 1) {
            return [$parts[0], 'Client'];
        }

        $lastName = array_pop($parts);

        return [implode(' ', $parts), $lastName];
    }

    private function resolveLocation(array $customer): array
    {
        $sucursal = strtolower((string) ($customer['sucursal'] ?? ''));
        $casilla = strtoupper((string) ($customer['casilla'] ?? ''));

        if (str_contains($sucursal, 'buenos aires') || str_starts_with($casilla, 'WWTBA')) {
            return [
                'branch_id' => $this->branchId('ARGENTINA'),
                'country_id' => 10,
                'state_id' => 214,
                'document_type_id' => $this->documentTypeId('DNI/Otro (ARG)'),
                'address' => 'Buenos Aires, Argentina',
                'postal_code' => '0000',
                'locality' => 'Buenos Aires',
            ];
        }

        if (str_contains($sucursal, 'miami') || str_starts_with($casilla, 'WWTMI')) {
            return [
                'branch_id' => $this->branchId('MIAMI'),
                'country_id' => 249,
                'state_id' => null,
                'document_type_id' => $this->documentTypeId('ID'),
                'address' => 'Miami, USA',
                'postal_code' => '00000',
                'locality' => 'Miami',
            ];
        }

        if (str_contains($sucursal, 'ciudad del este') || str_starts_with($casilla, 'WWTCD')) {
            return [
                'branch_id' => $this->branchId('PARAGUAY'),
                'country_id' => 171,
                'state_id' => null,
                'document_type_id' => $this->documentTypeId('Cédula de identidad (PY)'),
                'address' => 'Ciudad del Este, Paraguay',
                'postal_code' => '0000',
                'locality' => 'Ciudad del Este',
            ];
        }

        return [
            'branch_id' => $this->branchId('PARAGUAY'),
            'country_id' => 171,
            'state_id' => null,
            'document_type_id' => $this->documentTypeId('Cédula de identidad (PY)'),
            'address' => 'Asuncion, Paraguay',
            'postal_code' => '0000',
            'locality' => 'Asuncion',
        ];
    }

    private function branchId(string $branchName): int
    {
        $key = strtolower($branchName);
        if (isset($this->branchCache[$key])) {
            return $this->branchCache[$key];
        }

        $branch = Branches::whereRaw('LOWER(branch) = ?', [$key])->first();
        if (! $branch) {
            throw new RuntimeException("Branch not found: {$branchName}");
        }

        return $this->branchCache[$key] = (int) $branch->id;
    }

    private function documentTypeId(string $documentName): int
    {
        $key = strtolower($documentName);
        if (isset($this->documentTypeCache[$key])) {
            return $this->documentTypeCache[$key];
        }

        $documentType = DocumentTypes::whereRaw('LOWER(document_name) = ?', [$key])->first();
        if (! $documentType) {
            $documentType = DocumentTypes::query()->first();
        }

        if (! $documentType) {
            throw new RuntimeException('No document types configured in database.');
        }

        return $this->documentTypeCache[$key] = (int) $documentType->id;
    }

    private function resolveTypeAndOrigin(string $waybill): array
    {
        $normalized = strtoupper(str_replace(' ', '', $waybill));

        $typeChar = strlen($normalized) >= 3 ? $normalized[2] : 'A';
        $type = match ($typeChar) {
            'L' => 'land',
            'M' => 'maritime',
            default => 'air',
        };

        $originPrefix = substr($normalized, 0, 2);
        $originId = match ($originPrefix) {
            'ES' => 2,
            'PA' => 3,
            'CH' => 5,
            default => 1,
        };

        return [$type, $originId];
    }

    private function mapStatus(string $status): string
    {
        $normalized = strtolower(trim($status));

        return match (true) {
            str_contains($normalized, 'recib') => 'received',
            str_contains($normalized, 'embar') => 'embarked',
            str_contains($normalized, 'arrib') => 'arrived',
            str_contains($normalized, 'retir') => 'retired',
            str_contains($normalized, 'progres') || str_contains($normalized, 'curso') => 'in-progress',
            default => 'received',
        };
    }

    private function parsePackageDate(string $date): Carbon
    {
        $date = trim($date);
        if ($date === '') {
            return Carbon::now();
        }

        foreach (['d-m-Y', 'd/m/Y', 'Y-m-d'] as $format) {
            try {
                return Carbon::createFromFormat($format, $date)->startOfDay();
            } catch (\Throwable) {
            }
        }

        return Carbon::now();
    }

    private function readJson(string $path): array
    {
        if (! is_file($path)) {
            throw new RuntimeException("File not found: {$path}");
        }

        $payload = json_decode(file_get_contents($path), true);
        if (! is_array($payload)) {
            throw new RuntimeException("Invalid JSON: {$path}");
        }

        return $payload;
    }

    private function recordError(string $message): void
    {
        $this->stats['errors'][] = $message;
    }
}
