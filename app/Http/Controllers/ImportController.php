<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PpmpItemsCatalog;
use App\Models\PpmpItem;
use App\Imports\PpmpItemsCatalogImport;
use App\Imports\PpmpItemsImport;
use App\Imports\PrItemsImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function import_files(Request $request, $type, $id)
    {
        $files = $request->file('attachments');
        try {
            foreach ($files as $file){
                if ($type == 'ppmp_catalog')
                    Excel::import(new PpmpItemsCatalogImport, $file);
                if ($type == 'ppmp'){
                    $data = PpmpItemsCatalog::select('general_desc')->where('department', auth()->user()->department)->where('year', date('Y'))->get();
                    $valid_items = $data->map(function ($item) {
                        return $item->general_desc;
                    })->toArray();
                    Excel::import(new PpmpItemsImport($valid_items, $id), $file);
                }
                if($type == 'pr'){
                    $data = PpmpItem::select('ppmp_items.*', 'procurement_project_management_plans.pmo_end_user_dept as department', 'procurement_project_management_plans.year as year')
                        ->join('procurement_project_management_plans', 'ppmp_items.ppmp_id', '=', 'procurement_project_management_plans.id')
                        ->where('procurement_project_management_plans.pmo_end_user_dept', auth()->user()->department)
                        ->where('procurement_project_management_plans.status', 'Approved')
                        ->where('procurement_project_management_plans.year', date('Y'))
                        ->get();
                    $valid_items = $data->map(function ($item) {
                        return $item->code;
                    })->toArray();
                    Excel::import(new PrItemsImport($valid_items, $id), $file);
                }
            }
            return response()->json(['message' => 'File imported successfully.']);
        }
        catch (Exception $e) {
            return response()->json(['message' => 'Please ayusin mo file mo.'], 422);
        }

    }
}
