<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Exports\PrItemsExport;
use App\Exports\PpmpItemsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export_files(Request $request, $type, $id)
    {
        try {
            if ($type == 'ppmp')
                return new PpmpItemsExport($id);
            if($type == 'pr')
                return new PrItemsExport($id);
        }
        catch (Exception $e){

        }
    }
}
