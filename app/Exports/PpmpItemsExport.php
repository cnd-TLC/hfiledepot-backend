<?php

namespace App\Exports;

use App\Models\PpmpItemsCatalog;
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

class PpmpItemsExport implements FromQuery, Responsable, WithHeadings, WithStyles, WithMapping
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
            'Unit',
            'Quantity',
            'Mode',
            'Estimated_Budget',
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sept',
            'Oct',
            'Nov',
            'Dec',
            'PPMP_ID',
        ];
    }

    public function map($row): array
    {
        return [
            $row->general_desc,
            $row->quantity,
            $row->mode,
            $row->estimated_budget,
            $row->jan,
            $row->feb,
            $row->mar,
            $row->apr,
            $row->may,
            $row->jun,
            $row->jul,
            $row->aug,
            $row->sept,
            $row->oct,
            $row->nov,
            $row->dec,
            $this->id, 
        ];
    }

    public function query()
    {
        return PpmpItemsCatalog::select('general_desc')->where('department', auth()->user()->department)->where('year', date('Y'));
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
            'A1:Q1000' => [
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
