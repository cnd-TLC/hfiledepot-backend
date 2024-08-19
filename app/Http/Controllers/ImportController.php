<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrItem;
use App\Models\PpmpItem;

use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends Controller
{
    public function code($id)
    {
        # fix this if possible
        $department = explode('(', auth()->user()->department)[1];
        $department = explode(')', $department)[0].'-'.$id;
        $year = date('y');
        $lastCode = PpmpItem::where('ppmp_id', $id)->orderBy('id', 'desc')->value('code');

        if($lastCode){
            $splitCode = explode('-', $lastCode);
            $endCode = intval(end($splitCode));
            $latestCode = intval($endCode) + 1;
        }
        else
            $latestCode = 1;

        $code = str_pad($latestCode, 4, '0', STR_PAD_LEFT);

        $setCode = $department.$year.'-'.$code;

        return $setCode;
    }

    public function import_files(Request $request, $type, $id)
    {
        $files = $request->file('attachments');
        try {
            foreach ($files as $file){
                $iterator_ctr = 0;
                $reader =   IOFactory::createReader('Xlsx');
                $spreadsheet = $reader->load($file);

                $sheet = $spreadsheet->getActiveSheet();

                $data = $sheet->getRowIterator();


                foreach ($data as $row) {
                    $cell_array = [];
                    $cells = $row->getCellIterator();

                    foreach($cells as $cell){
                        $cell_array[] = $cell;

                        if ($row->isEmpty())
                            break;
                    }

                    if ($type == 'ppmp') {
                        if (($cell_array[0] == '' || $cell_array[1] == '' || $cell_array[4] == '' || $cell_array[5] == '' || $cell_array[18] == '') && $iterator_ctr > 2)
                            break;

                        $iterator_ctr++;

                        if ($iterator_ctr <= 2) 
                            continue;

                        if ($cell_array[18] != $id)
                            return response()->json(['message' => 'Stopped importing at line '.$iterator_ctr.'. PPMP ID didn\'t match.', 'status' => 'failed']);
                        
                        $item = new PpmpItem;
                        $item->code = $this->code($id);
                        $item->category = $cell_array[0];
                        $item->general_desc = $cell_array[1];
                        $item->unit = $cell_array[2];
                        $item->quantity = $cell_array[3] != '' ? $cell_array[3] : null;
                        $item->lumpsum = $item->quantity == null ? 1 : 0;
                        $item->mode_of_procurement = $cell_array[4];
                        $item->estimated_budget = $cell_array[5];
                        $item->jan = $cell_array[6];
                        $item->feb = $cell_array[7];
                        $item->mar = $cell_array[8];
                        $item->apr = $cell_array[9];
                        $item->may = $cell_array[10];
                        $item->jun = $cell_array[11];
                        $item->jul = $cell_array[12];
                        $item->aug = $cell_array[13];
                        $item->sept = $cell_array[14];
                        $item->oct = $cell_array[15];
                        $item->nov = $cell_array[16];
                        $item->dec = $cell_array[17];
                        $item->ppmp_id = $cell_array[18];
                        $result = $item->save();
                    }
                    else if ($type == 'pr'){
                        if (($cell_array[0] == '' || $cell_array[1] == '' || $cell_array[3] == '') && $iterator_ctr > 2)
                            break;

                        $iterator_ctr++;

                        if ($iterator_ctr <= 2) 
                            continue;

                        if ($cell_array[3] != $id)
                            return response()->json(['message' => 'Stopped importing at line '.$iterator_ctr.'. PR ID didn\'t match.', 'status' => 'failed']);

                        $ppmp_item = PpmpItem::where('code', $cell_array[0])->first();

                        $item = new PrItem;
                        $item->item_no = $cell_array[0];
                        $item->unit = $ppmp_item->unit;
                        $item->category = $ppmp_item->category;
                        $item->item_description = $ppmp_item->general_desc;
                        $item->quantity = $cell_array[2] != '' ? $cell_array[2] : null;
                        $item->lumpsum = $item->quantity == null ? 1 : 0;
                        $item->unit_cost = $cell_array[1];
                        $item->mode_of_procurement = $ppmp_item->mode_of_procurement;
                        $item->remarks = $ppmp_item->remarks;
                        $item->pr_id = $cell_array[3];
                        $result = $item->save();
                    }
                }
            }
            if ($iterator_ctr == 2)
                return response()->json(['message' => 'File imported successfully. No items found.', 'status' => 'success']);

            return response()->json(['message' => 'Successfully imported '. $iterator_ctr-2 .' items, stopping at line '.$iterator_ctr.'.', 'status' => 'success']);
        }
        catch (Exception $e) {
            return response()->json(['message' => 'Please check your file.'], 500);
        }

    }
}
