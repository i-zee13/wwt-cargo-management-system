<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\Lang;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class PackagesExport implements FromArray, WithEvents
{
    protected $filters;
    protected $organizationName;
    protected $organizationLogoBase64;
    protected $selectedColumns;

    protected const COLUMN_MAP = [
        'guide' => 'fields.id',
        'waybill' => 'fields.waybill',
        'origin' => 'fields.origin',
        'branch' => 'fields.destination',
        'type' => 'fields.type',
        'description' => 'fields.description',
        'tracking' => 'fields.original_tracking',
        'date' => 'fields.date',
        'kg' => 'fields.kg',
        'cbm' => 'fields.cbm',
        'client' => 'fields.client',
        'status' => 'fields.package_status',
        'total' => 'fields.grand_total',
    ];

    public function __construct(array $filters, string $organizationName, ?string $organizationLogoBase64)
    {
        $this->filters = $filters;
        $this->organizationName = $organizationName;
        $this->organizationLogoBase64 = $organizationLogoBase64;
        $this->selectedColumns = $this->resolveSelectedColumns($filters['columns'] ?? null);
    }

    protected function resolveSelectedColumns($columns): array
    {
        $allowed = array_keys(self::COLUMN_MAP);
        if (! is_array($columns) || count($columns) === 0) {
            return $allowed;
        }

        $selected = array_values(array_intersect($allowed, $columns));

        return count($selected) > 0 ? $selected : $allowed;
    }

    protected function headerLabels(): array
    {
        $headers = [];
        foreach ($this->selectedColumns as $key) {
            $headers[] = Lang::get(self::COLUMN_MAP[$key]);
        }

        return $headers;
    }

    public function array(): array
    {
        $rows = [];
        $rows[] = [];
        $rows[] = [$this->organizationName];

        $headers = $this->headerLabels();
        $rows[] = $headers;
        $rows[] = $headers;

        foreach ($this->getData() as $record) {
            $rows[] = array_values($record);
        }

        return $rows;
    }

    protected function getData()
    {
        $query = DB::table('packages')
            ->leftjoin('clients', 'packages.client_id', '=', 'clients.id')
            ->leftjoin('origins', 'packages.origin_id', '=', 'origins.id')
            ->leftjoin('branches', 'clients.branch_id', '=', 'branches.id')
            ->select(
                'packages.id AS guide',
                'packages.waybill AS waybill',
                'origins.origin_name AS origin',
                'packages.description AS description',
                'packages.type AS type',
                'packages.original_tracking AS tracking',
                'packages.created_at AS date',
                'packages.kg AS kg',
                'packages.cbm AS cbm',
                'clients.first_name AS client',
                'clients.last_name AS client_last',
                'clients.suite AS suite',
                'branches.branch AS branch',
                'packages.status AS status',
                'packages.grand_total AS total',
                'packages.status_change_date AS delivered'
            );

        if (! empty($this->filters['origins']) && $this->filters['origins'][0] !== 'all') {
            $query->whereIn('packages.origin_id', $this->filters['origins']);
        }
        if (! empty($this->filters['branches']) && $this->filters['branches'][0] !== 'all') {
            $query->whereIn('clients.branch_id', $this->filters['branches']);
        }
        if (! empty($this->filters['statuses']) && $this->filters['statuses'][0] !== 'all') {
            $query->whereIn('packages.status', $this->filters['statuses']);
        }
        if (! empty($this->filters['types']) && $this->filters['types'][0] !== 'all') {
            $query->whereIn('packages.type', $this->filters['types']);
        }
        if (! empty($this->filters['clients']) && $this->filters['clients'][0] !== 'all') {
            $query->whereIn('packages.client_id', $this->filters['clients']);
        }
        if (($this->filters['filter_date'] ?? 1) != 1) {
            if (! empty($this->filters['start_date']) && ! empty($this->filters['end_date'])) {
                $startDate = \Carbon\Carbon::parse($this->filters['start_date'])->startOfDay();
                $endDate = \Carbon\Carbon::parse($this->filters['end_date'])->endOfDay();
                $query->whereBetween('packages.created_at', [$startDate, $endDate]);
            }
        }

        $packages = $query->get();

        return $packages->map(function ($item) {
            $full = [
                'guide' => $item->guide,
                'waybill' => $item->waybill,
                'origin' => $item->origin,
                'branch' => $item->branch,
                'type' => $item->type,
                'description' => $item->description,
                'tracking' => $item->tracking,
                'date' => $item->date,
                'kg' => $item->kg,
                'cbm' => $item->cbm,
                'client' => $item->client.' '.$item->client_last.' ('.$item->suite.')',
                'status' => ucwords(str_replace('-', ' ', $item->status)),
                'total' => $item->total,
            ];

            $row = [];
            foreach ($this->selectedColumns as $key) {
                $row[Lang::get(self::COLUMN_MAP[$key])] = $full[$key] ?? '';
            }

            return $row;
        });
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                if ($this->organizationLogoBase64) {
                    $imageParts = explode(',', $this->organizationLogoBase64);
                    if (count($imageParts) >= 2) {
                        $imageData = base64_decode($imageParts[1]);
                        $tempImagePath = tempnam(sys_get_temp_dir(), 'logo_').'.png';
                        file_put_contents($tempImagePath, $imageData);

                        $drawing = new Drawing();
                        $drawing->setName('Organization Logo');
                        $drawing->setDescription('Logo');
                        $drawing->setPath($tempImagePath);
                        $drawing->setHeight(80);
                        $drawing->setWidth(400);
                        $drawing->setCoordinates('A1');
                        $drawing->setOffsetX(10);
                        $drawing->setOffsetY(5);
                        $drawing->setWorksheet($sheet);

                        $sheet->setCellValue('A1', '');

                        register_shutdown_function(function () use ($tempImagePath) {
                            if (file_exists($tempImagePath)) {
                                unlink($tempImagePath);
                            }
                        });
                    }
                }

                $sheet->getRowDimension(1)->setRowHeight(80);
                $sheet->getRowDimension(3)->setRowHeight(20);

                $headersCount = count($this->selectedColumns);
                $lastColumnLetter = Coordinate::stringFromColumnIndex($headersCount);

                $sheet->mergeCells("A1:{$lastColumnLetter}1");
                $sheet->mergeCells("A2:{$lastColumnLetter}2");
                $sheet->setCellValue("A2", $this->organizationName.' Packages Report');
                $sheet->getStyle("A1:{$lastColumnLetter}1")->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                $sheet->getStyle("A2:{$lastColumnLetter}2")->applyFromArray([
                    'font' => [
                        'size' => 18,
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $sheet->getStyle("A3:{$lastColumnLetter}3")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'FFEB973C'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $sheet->getStyle("A3:{$lastColumnLetter}3")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                foreach (range(1, $headersCount) as $index) {
                    $column = Coordinate::stringFromColumnIndex($index);
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                $sheet->freezePane('A4');

                $highestRow = $sheet->getHighestRow();
                $totalRow = $highestRow + 1;
                $sheet->setCellValue("A{$totalRow}", 'Total');
                $sheet->getStyle("A{$totalRow}")->getFont()->setBold(true);

                $kgIndex = array_search('kg', $this->selectedColumns, true);
                $totalIndex = array_search('total', $this->selectedColumns, true);

                if ($kgIndex !== false) {
                    $kgCol = Coordinate::stringFromColumnIndex($kgIndex + 1);
                    $sheet->setCellValue("{$kgCol}{$totalRow}", "=SUM({$kgCol}4:{$kgCol}{$highestRow})");
                    $sheet->getStyle("{$kgCol}{$totalRow}")->getFont()->setBold(true);
                }
                if ($totalIndex !== false) {
                    $totalCol = Coordinate::stringFromColumnIndex($totalIndex + 1);
                    $sheet->setCellValue("{$totalCol}{$totalRow}", "=SUM({$totalCol}4:{$totalCol}{$highestRow})");
                    $sheet->getStyle("{$totalCol}{$totalRow}")->getFont()->setBold(true);
                }

                $sheet->getStyle("A{$totalRow}:{$lastColumnLetter}{$totalRow}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle("A{$totalRow}:{$lastColumnLetter}{$totalRow}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'D3D3D3'],
                    ],
                ]);
            },
        ];
    }
}
