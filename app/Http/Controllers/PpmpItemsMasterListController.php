<?php

namespace App\Http\Controllers;
use App\Models\PpmpItem;

use Illuminate\Http\Request;

class PpmpItemsMasterListController extends Controller
{
    public function index(Request $request, $size)
    {
        if (auth()->user()->department == 'Bids and Awards Committee (BAC)')
            $data = PpmpItem::where(function ($query) use ($request) {
                        $query->where('general_desc', 'LIKE', "%$request->search%")
                            ->orWhere('year', 'LIKE', "%$request->search%")
                            ->orWhere('pmo_end_user_dept', 'LIKE', "%$request->search%")
                            ->orWhere('unit', 'LIKE', "%$request->search%");
                        })
                        ->where('status', 'Approved')
                        ->join('procurement_project_management_plans', 'ppmp_items.ppmp_id', '=', 'procurement_project_management_plans.id')
                        ->paginate($size);
        else
            $data = PpmpItem::where(function ($query) use ($request) {
                        $query->where('general_desc', 'LIKE', "%$request->search%")
                            ->orWhere('year', 'LIKE', "%$request->search%")
                            ->orWhere('pmo_end_user_dept', 'LIKE', "%$request->search%")
                            ->orWhere('unit', 'LIKE', "%$request->search%");
                        })
                        ->where('pmo_end_user_dept', auth()->user()->department)
                        ->where('status', 'Approved')
                        ->join('procurement_project_management_plans', 'ppmp_items.ppmp_id', '=', 'procurement_project_management_plans.id')
                        ->paginate($size);

        return response()->json([
            'retrievedData' => $data->items(),
            'currentPage' => $data->currentPage(),
            'perPage' => $data->perPage(),
            'total' => $data->total(),
            'lastPage' => $data->lastPage(),
            'nextPageUrl' => $data->nextPageUrl(),
            'previousPageUrl' => $data->previousPageUrl(),
        ]);
    }
}
