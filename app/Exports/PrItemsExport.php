<?php

namespace App\Exports;

use App\Models\PpmpItem;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Maatwebsite\Excel\Concerns\WithMapping;

class PrItemsExport implements FromQuery, Responsable, WithHeadings, WithStyles, WithMapping
{
    use Exportable;

    private $id;

    private $fileName = 'ppmp_items_template.xlsx';

    private $writerType = Excel::XLSX;

    private $headers = [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function headings(): array
    {
        return [
            'Code',
            'Item',
            'Quantity',
            'Unit_Cost',
            'PR_ID'
        ];
    }

    public function map($row): array
    {
        return [
            $row->code,
            $row->general_desc,
            $row->quantity,
            $row->unit_cost,
            $this->id,  // Add PPMP_ID here
        ];
    }

    public function query()
    {
        $currentMonth = date('M');
        $monthCol = strtolower($currentMonth);

        return PpmpItem::select('ppmp_items.code', 'ppmp_items.general_desc', "ppmp_items.$monthCol as quantity")
                ->join('procurement_project_management_plans', 'ppmp_items.ppmp_id', '=', 'procurement_project_management_plans.id')
                ->where('procurement_project_management_plans.pmo_end_user_dept', auth()->user()->department)
                ->whereNotNull("ppmp_items.$monthCol")
                ->where('procurement_project_management_plans.status', 'Approved')
                ->where('procurement_project_management_plans.year', date('Y'));
    }

    public function styles($sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ],
            'A1:E1000' => [
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                    'inside' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ],
        ];
    }
}
