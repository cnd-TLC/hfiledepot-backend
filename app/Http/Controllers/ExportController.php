<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PpmpItem;

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \PhpOffice\PhpSpreadsheet\Style\Alignment;
use \PhpOffice\PhpSpreadsheet\Style\Border;
use \PhpOffice\PhpSpreadsheet\NamedRange;
use \PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class ExportController extends Controller
{
    public function get_ppmp_items()
    {
        $currentMonth = date('M');
        $monthCol = strtolower($currentMonth);

        $data = PpmpItem::select('ppmp_items.*', 'procurement_project_management_plans.pmo_end_user_dept as department', 'procurement_project_management_plans.year as year', "ppmp_items.$monthCol as quantity")
                ->join('procurement_project_management_plans', 'ppmp_items.ppmp_id', '=', 'procurement_project_management_plans.id')
                ->where('procurement_project_management_plans.pmo_end_user_dept', auth()->user()->department)
                ->whereNotNull("ppmp_items.$monthCol")
                ->where('procurement_project_management_plans.status', 'Approved')
                ->where('procurement_project_management_plans.year', date('Y')) 
                ->get();

        return $data;
    }

    public function export_files(Request $request, $type)
    {
        if ($type == 'pr') {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                        ->setTitle('Purchase Request Template')
                        ->setDescription('PR Template for Sorsogon City LGU');

            $mergedHeadingStyle = [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ];

            $headingStyle = [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ];

            $subHeadingStyle = [
                'font' => [
                    'italic' => true,
                    'size' => 8,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'bottom' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                    'top' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                ],
            ];

            $ACDContentStyle = [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ];

            $BContentStyle = [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ];


            // Import Sheet

            $import_sheet = $spreadsheet->getActiveSheet();
            $import_sheet->setTitle('Import');

            $import_sheet->setCellValue('A1', 'Code');
            $import_sheet->setCellValue('B1', 'Unit_Cost');
            $import_sheet->setCellValue('C1', 'Quantity');
            $import_sheet->setCellValue('D1', 'PR_ID');

            $import_sheet->setCellValue('A2', 'select from dropdown or check items sheet');
            $import_sheet->setCellValue('C2', ' leave blank if lumpsum');
            $import_sheet->mergeCells('B1:B2', Worksheet::MERGE_CELL_CONTENT_HIDE);
            $import_sheet->mergeCells('D1:D2', Worksheet::MERGE_CELL_CONTENT_HIDE);

            $import_sheet->getStyle('B1:B2')->applyFromArray($mergedHeadingStyle);
            $import_sheet->getStyle('D1:D2')->applyFromArray($mergedHeadingStyle);
            $import_sheet->getStyle('A1')->applyFromArray($headingStyle);
            $import_sheet->getStyle('C1')->applyFromArray($headingStyle);
            $import_sheet->getStyle('A2:D2')->applyFromArray($subHeadingStyle);
            $import_sheet->getStyle('A3:A1000')->applyFromArray($ACDContentStyle);
            $import_sheet->getStyle('B3:B1000')->applyFromArray($BContentStyle);
            $import_sheet->getStyle('C3:C1000')->applyFromArray($ACDContentStyle);
            $import_sheet->getStyle('D3:D1000')->applyFromArray($ACDContentStyle);

            $import_sheet->getColumnDimension('A')->setWidth(120, 'pt');
            $import_sheet->getColumnDimension('B')->setWidth(100, 'pt');
            $import_sheet->getColumnDimension('C')->setWidth(60, 'pt');


            // Items Sheet

            $new_items_sheet = new Worksheet($spreadsheet, 'Items');
            $spreadsheet->addSheet($new_items_sheet);

            $items_sheet = $spreadsheet->setActiveSheetIndexByName('Items');

            $items = $this->get_ppmp_items();

            $iterator_ctr = 1;
            
            $items_sheet->setCellValue("A$iterator_ctr", 'Code');
            $items_sheet->setCellValue("B$iterator_ctr", 'Category');
            $items_sheet->setCellValue("C$iterator_ctr", 'General_Description');
            $items_sheet->setCellValue("D$iterator_ctr", 'Unit');
            $items_sheet->setCellValue("E$iterator_ctr", 'Quantity');
            $items_sheet->setCellValue("F$iterator_ctr", 'Mode');
            $items_sheet->setCellValue("G$iterator_ctr", 'Estimated_Budget');
            $items_sheet->setCellValue("H$iterator_ctr", 'Jan');
            $items_sheet->setCellValue("I$iterator_ctr", 'Feb');
            $items_sheet->setCellValue("J$iterator_ctr", 'Mar');
            $items_sheet->setCellValue("K$iterator_ctr", 'Apr');
            $items_sheet->setCellValue("L$iterator_ctr", 'May');
            $items_sheet->setCellValue("M$iterator_ctr", 'Jun');
            $items_sheet->setCellValue("N$iterator_ctr", 'Jul');
            $items_sheet->setCellValue("O$iterator_ctr", 'Aug');
            $items_sheet->setCellValue("P$iterator_ctr", 'Sept');
            $items_sheet->setCellValue("Q$iterator_ctr", 'Oct');
            $items_sheet->setCellValue("R$iterator_ctr", 'Nov');
            $items_sheet->setCellValue("S$iterator_ctr", 'Dec');

            foreach($items as $item){
                $iterator_ctr++;
                
                $items_sheet->setCellValue("A$iterator_ctr", $item->code);
                $items_sheet->setCellValue("B$iterator_ctr", $item->category);
                $items_sheet->setCellValue("C$iterator_ctr", $item->general_desc);
                $items_sheet->setCellValue("D$iterator_ctr", $item->unit);
                $items_sheet->setCellValue("E$iterator_ctr", $item->quantity ? $item->quantity : 'lumpsum');
                $items_sheet->setCellValue("F$iterator_ctr", $item->mode_of_procurement);
                $items_sheet->setCellValue("G$iterator_ctr", $item->estimated_budget);
                $items_sheet->setCellValue("H$iterator_ctr", $item->jan);
                $items_sheet->setCellValue("I$iterator_ctr", $item->feb);
                $items_sheet->setCellValue("J$iterator_ctr", $item->mar);
                $items_sheet->setCellValue("K$iterator_ctr", $item->apr);
                $items_sheet->setCellValue("L$iterator_ctr", $item->may);
                $items_sheet->setCellValue("M$iterator_ctr", $item->jun);
                $items_sheet->setCellValue("N$iterator_ctr", $item->jul);
                $items_sheet->setCellValue("O$iterator_ctr", $item->aug);
                $items_sheet->setCellValue("P$iterator_ctr", $item->sept);
                $items_sheet->setCellValue("Q$iterator_ctr", $item->oct);
                $items_sheet->setCellValue("R$iterator_ctr", $item->nov);
                $items_sheet->setCellValue("S$iterator_ctr", $item->dec);
            }

            $items_sheet->getStyle('A1:S1')->applyFromArray($headingStyle);
            $items_sheet->getStyle("A1:S$iterator_ctr")->applyFromArray($ACDContentStyle);

            $items_sheet->getColumnDimension('A')->setAutoSize(true);
            $items_sheet->getColumnDimension('B')->setAutoSize(true);
            $items_sheet->getColumnDimension('C')->setAutoSize(true);
            $items_sheet->getColumnDimension('G')->setAutoSize(true);

            if ($iterator_ctr > 1){
                $spreadsheet->addNamedRange( new NamedRange('Codes', $items_sheet, '=$A$2:$A$'.$iterator_ctr) );

                // #setting default active sheet

                $spreadsheet->setActiveSheetIndexByName('Import');

                for($current_iteration = 3; $current_iteration <= 1000; $current_iteration++) {
                    $validation = $spreadsheet->getActiveSheet()->getCell('A'.$current_iteration)->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Error');
                    $validation->setError('Please refer to items sheet for for correct values!');
                    $validation->setPromptTitle('Code');
                    $validation->setPrompt('Refer to items sheet for for correct values.');
                    $validation->setFormula1('=Codes');
                }
            }

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $file = 'pr_template.xlsx';
            $writer->save($file);

            $spreadsheet_blob = file_get_contents($file);

            return response()->download($file, 'pr_template.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="pr_template.xlsx"',
                'Content-Length' => strlen($spreadsheet_blob),
            ]);
        }
        #elseif here
    }
}
