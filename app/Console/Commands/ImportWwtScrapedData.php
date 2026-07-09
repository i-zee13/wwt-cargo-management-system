<?php

namespace App\Console\Commands;

use App\Services\WwtScrapedDataImporter;
use Illuminate\Console\Command;

class ImportWwtScrapedData extends Command
{
    protected $signature = 'wwt:import-scraped
        {--packages= : Path to packages.json from scraper}
        {--customers= : Path to customers.json from scraper}
        {--dry-run : Validate and report without writing to DB}
        {--all-customers : Import all customers, not only those with packages}
        {--force-update-clients : Update existing clients matched by suite/email}
        {--import-duplicate-packages : Insert packages even if original_tracking already exists}
        {--admin-id=1 : Admin user id for created_by/updated_by}
        {--password=WwtImport@2026 : Default password for newly created clients}';

    protected $description = 'Import scraped WWT customers and their related packages (customer first, then packages).';

    public function handle(): int
    {
        $packagesPath = $this->option('packages')
            ?: base_path('../python scrapper/output/packages.json');
        $customersPath = $this->option('customers')
            ?: base_path('../python scrapper/output/customers.json');

        if (! is_file($packagesPath)) {
            $this->error("Packages file not found: {$packagesPath}");
            $this->line('Use --packages=/full/path/to/packages.json');

            return self::FAILURE;
        }

        if (! is_file($customersPath)) {
            $this->error("Customers file not found: {$customersPath}");
            $this->line('Use --customers=/full/path/to/customers.json');

            return self::FAILURE;
        }

        $this->info('WWT scraped data import');
        $this->line("Packages:  {$packagesPath}");
        $this->line("Customers: {$customersPath}");

        if ($this->option('dry-run')) {
            $this->warn('Dry run mode: no database writes.');
        }

        $importer = new WwtScrapedDataImporter(
            dryRun: (bool) $this->option('dry-run'),
            onlyWithPackages: ! $this->option('all-customers'),
            skipExistingPackages: ! $this->option('import-duplicate-packages'),
            updateExistingClients: (bool) $this->option('force-update-clients'),
            createdBy: (int) $this->option('admin-id'),
            defaultPassword: (string) $this->option('password'),
        );

        try {
            $stats = $importer->import($packagesPath, $customersPath);
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->newLine();
        $this->table(
            ['Metric', 'Count'],
            collect($stats)
                ->except('errors')
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
        $this->info('Import finished.');

        return empty($stats['errors']) ? self::SUCCESS : self::FAILURE;
    }
}
