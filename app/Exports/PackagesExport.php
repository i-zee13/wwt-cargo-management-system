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

    /**
     * Constructor to pass filters and organization details
     *
     * @param array $filters
     * @param string $organizationName
     * @param string|null $organizationLogoBase64
     */
    public function __construct(array $filters, string $organizationName, ?string $organizationLogoBase64)
    {
        $this->filters = $filters;
        $this->organizationName = $organizationName;
        $this->organizationLogoBase64 = $organizationLogoBase64;
    }

    /**
     * Define the array of rows for the Excel sheet
     *
     * @return array
     */
    public function array(): array
    {
        $rows = [];

        // Row 1: Empty (logo will be embedded via drawings)
        $rows[] = []; 

        // Row 2: Organization Name
        $rows[] = [$this->organizationName];

        // Row 3: Headers
        $headers = [
            Lang::get('fields.id'),
            Lang::get('fields.waybill'),
            Lang::get('fields.origin'),
            Lang::get('fields.destination'),
            Lang::get('fields.type'),
            Lang::get('fields.description'),
            Lang::get('fields.original_tracking'),
            Lang::get('fields.date'),
            Lang::get('fields.kg'),
            Lang::get('fields.cbm'),
            Lang::get('fields.client'),
            Lang::get('fields.package_status'),
            Lang::get('fields.grand_total'),
        ];
        $rows[] = $headers;
        $rows[] = $headers;


        // Row 4 onwards: Data Records
        $dataRecords = $this->getData();

        foreach ($dataRecords as $record) {
        

            $rows[] = array_values($record);
        }


        return $rows;
    }

    /**
     * Fetch and map data based on filters
     *
     * @return \Illuminate\Support\Collection
     */
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

        // Apply filters
        if (!empty($this->filters['origins']) && $this->filters['origins'][0] !== 'all') {
            $query->whereIn('packages.origin_id', $this->filters['origins']);
        }
        if (!empty($this->filters['branches']) && $this->filters['branches'][0] !== 'all') {
            $query->whereIn('clients.branch_id', $this->filters['branches']);
        }
        if (!empty($this->filters['statuses']) && $this->filters['statuses'][0] !== 'all') {
            $query->whereIn('packages.status', $this->filters['statuses']);
        }
        if (!empty($this->filters['types']) && $this->filters['types'][0] !== 'all') {
            $query->whereIn('packages.type', $this->filters['types']);
        }
        if (!empty($this->filters['clients']) && $this->filters['clients'][0] !== 'all') {
            $query->whereIn('packages.client_id', $this->filters['clients']);
        }
        if ($this->filters['filter_date'] != 1) {
            if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) { 
                $startDate = \Carbon\Carbon::parse($this->filters['start_date'])->startOfDay();
                $endDate = \Carbon\Carbon::parse($this->filters['end_date'])->endOfDay(); 
                $query->whereBetween('packages.created_at', [$startDate, $endDate]);
            }
            
        }
        

        $packages = $query->get();

        return $packages->map(function ($item) {
            return [
                Lang::get('fields.id') => $item->guide,
                Lang::get('fields.waybill') => $item->waybill,
                Lang::get('fields.origin') => $item->origin,
                Lang::get('fields.destination') => $item->branch,
                Lang::get('fields.type') => $item->type,
                Lang::get('fields.description') => $item->description,
                Lang::get('fields.original_tracking') => $item->tracking,
                Lang::get('fields.date') => $item->date,
                Lang::get('fields.kg') => $item->kg,
                Lang::get('fields.cbm') => $item->cbm,
                Lang::get('fields.client') => $item->client . ' ' . $item->client_last . ' (' . $item->suite . ')',
                Lang::get('fields.package_status') => ucwords(str_replace('-', ' ', $item->status)),
                Lang::get('fields.grand_total') => $item->total,
            ];
        });
    }

    /**
     * Register events to handle styling and embedding the logo
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Embed the logo in A1
                if ($this->organizationLogoBase64) {
                    $imageParts = explode(',', $this->organizationLogoBase64);
                    if (count($imageParts) >= 2) {
                        $imageData = base64_decode($imageParts[1]);
                        $tempImagePath = tempnam(sys_get_temp_dir(), 'logo_') . '.png';
                        file_put_contents($tempImagePath, $imageData);
                
                        $drawing = new Drawing();
                        $drawing->setName('Organization Logo'); // This doesn't affect the cell text
                        $drawing->setDescription('Logo');
                        $drawing->setPath($tempImagePath);
                
                        // Adjust the size of the image
                        $drawing->setHeight(80); // Increase height for larger image
                        $drawing->setWidth(400); // Optional: set width if required
                
                        // Place the image in A1 without adding text to the cell
                        $drawing->setCoordinates('A1');
                        $drawing->setOffsetX(10);
                        $drawing->setOffsetY(5);
                        $drawing->setWorksheet($sheet);
                
                        // Clear any text in A1
                        $sheet->setCellValue('A1', '');
                
                        // Cleanup the temporary file after use
                        register_shutdown_function(function () use ($tempImagePath) {
                            if (file_exists($tempImagePath)) {
                                unlink($tempImagePath);
                            }
                        });
                    }
                }

                // Set row heights
                $sheet->getRowDimension(1)->setRowHeight(80); // Logo row
    
                $sheet->getRowDimension(3)->setRowHeight(20); // Headers row
    

                // Define the number of headers
                $headersCount = count([
                    Lang::get('fields.id'),
                    Lang::get('fields.waybill'),
                    Lang::get('fields.origin'),
                    Lang::get('fields.destination'),
                    Lang::get('fields.type'),
                    Lang::get('fields.description'),
                    Lang::get('fields.original_tracking'),
                    Lang::get('fields.date'),
                    Lang::get('fields.kg'),
                    Lang::get('fields.cbm'),
                    Lang::get('fields.client'),
                    Lang::get('fields.package_status'),
                    Lang::get('fields.grand_total'),
                ]);
                $lastColumnLetter = Coordinate::stringFromColumnIndex($headersCount);

                // Merge cells for the organization name
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

                // Apply header styling (Row 3)
                // **Ensure that the styling is applied to row 3, not row 2**
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

                // Set borders for headers
                $sheet->getStyle("A3:{$lastColumnLetter}3")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Auto-size columns
                foreach (range('A', $lastColumnLetter) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Freeze panes so that headers are always visible
                $sheet->freezePane('A4');

                // Add total row
                $highestRow = $sheet->getHighestRow(); // Get the last row with data
    
                $totalRow = $highestRow + 1;

                // Set 'Total' label in the first cell of the total row
                $sheet->setCellValue("A{$totalRow}", 'Total');

                // Apply bold font to 'Total'
                $sheet->getStyle("A{$totalRow}")->getFont()->setBold(true);

                // Set sum formulas for 'kg' (I) and 'grand_total' (M)
                // Ensure that 'kg' is column I and 'grand_total' is column M
                $sheet->setCellValue("I{$totalRow}", "=SUM(I4:I{$highestRow})");
                $sheet->setCellValue("M{$totalRow}", "=SUM(M4:M{$highestRow})");

                // Apply bold font to the sum cells
                $sheet->getStyle("I{$totalRow}:M{$totalRow}")->getFont()->setBold(true);

                // Optionally, apply a border around the total row
                $sheet->getStyle("A{$totalRow}:{$lastColumnLetter}{$totalRow}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Optionally, fill the total row with a background color
                $sheet->getStyle("A{$totalRow}:{$lastColumnLetter}{$totalRow}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'D3D3D3'], // Light color for distinction
                    ],
                ]);
            },
        ];
    }
}
