<?php

namespace App\Console\Commands;

use App\Services\WwtScrapedDataImporter;
use Illuminate\Console\Command;

class ImportWwtClients extends Command
{
    protected $signature = 'wwt:import-clients
        {--customers= : Path to customers.json from scraper}
        {--dry-run : Validate and report without writing to DB}
        {--force-update : Update existing clients matched by suite/email}
        {--admin-id=1 : Admin user id for created_by/updated_by}
        {--password=WwtImport@2026 : Default password for newly created clients}';

    protected $description = 'Import scraped WWT customers into the clients table only (no packages).';

    public function handle(): int
    {
        $customersPath = $this->option('customers')
            ?: base_path('../python scrapper/output/customers.json');

        if (! is_file($customersPath)) {
            $this->error("Customers file not found: {$customersPath}");
            $this->line('Use --customers=/full/path/to/customers.json');

            return self::FAILURE;
        }

        $this->info('WWT clients-only import');
        $this->line("Customers: {$customersPath}");

        if ($this->option('dry-run')) {
            $this->warn('Dry run mode: no database writes.');
        }

        $importer = new WwtScrapedDataImporter(
            dryRun: (bool) $this->option('dry-run'),
            onlyWithPackages: false,
            skipExistingPackages: true,
            updateExistingClients: (bool) $this->option('force-update'),
            createdBy: (int) $this->option('admin-id'),
            defaultPassword: (string) $this->option('password'),
        );

        try {
            $stats = $importer->importClientsOnly($customersPath);
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->newLine();
        $this->table(
            ['Metric', 'Count'],
            collect($stats)
                ->only([
                    'customers_processed',
                    'customers_created',
                    'customers_updated',
                    'customers_skipped',
                ])
                ->map(fn ($value, $key) => [$key, $value])
                ->values()
                ->all()
        );

        if (! empty($stats['errors'])) {
            $this->newLine();
            $this->warn('Errors:');
            foreach ($stats['errors'] as $error) {
                $this->line("- {$error}");
            }
        }

        $this->newLine();
        $this->info('Clients import finished.');

        return empty($stats['errors']) ? self::SUCCESS : self::FAILURE;
    }
}
