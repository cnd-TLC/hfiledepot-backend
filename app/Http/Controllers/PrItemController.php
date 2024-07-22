<?php

namespace App\Http\Controllers;
use App\Models\PrItem;
use App\Models\PpmpItem;

use Illuminate\Http\Request;

class PrItemController extends Controller
{
    public function index_department()
    {
        $data = PpmpItem::select('ppmp_items.*', 'procurement_project_management_plans.pmo_end_user_dept as department', 'procurement_project_management_plans.year as year')
                ->join('procurement_project_management_plans', 'ppmp_items.ppmp_id', '=', 'procurement_project_management_plans.id')
                ->where('procurement_project_management_plans.pmo_end_user_dept', auth()->user()->department)
                ->where('procurement_project_management_plans.status', 'Approved')
                ->where('procurement_project_management_plans.year', date('Y'))
                ->get();

        return response()->json([
            'retrievedData' => $data
        ]);
    }

    public function index($id, $size)
    {
        $data = PrItem::where('pr_id', $id)->paginate($size);

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

    public function store(Request $request)
    {
        $item = new PrItem;
        $item->item_no = $request->item_no;
        $item->unit = $request->unit;
        $item->category = $request->category;
        $item->item_description = $request->item_description;
        $item->quantity = $request->quantity;
        $item->unit_cost = $request->unit_cost;
        $item->lumpsum = $request->lumpsum;
        $item->mode_of_procurement = $request->mode_of_procurement;
        $item->remarks = $request->remarks;
        $item->pr_id = $request->pr_id;
        $result = $item->save();

        if ($result)
            return response()->json([
                'message' => 'Item successfully added.'
            ]);
        return response()->json([
            'message' => 'Cannot add the item.'
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $item = PrItem::find($id);
        $item->item_no = $request->item_no;
        $item->unit = $request->unit;
        $item->category = $request->category;
        $item->item_description = $request->item_description;
        $item->quantity = $request->quantity;
        $item->unit_cost = $request->unit_cost;
        $item->lumpsum = $request->lumpsum;
        $item->mode_of_procurement = $request->mode_of_procurement;
        $item->remarks = $request->remarks;
        $item->pr_id = $request->pr_id;
        $result = $item->save();

        if ($result)
            return response()->json([
                'message' => 'Item successfully updated.'
            ]);
        return response()->json([
            'message' => 'Cannot update the item.'
        ], 400);
    }

    public function destroy($id)
    {
        $item = PrItem::find($id);
        $result = $item->delete();

        if ($result)
            return response()->json([
                'message' => 'Item successfully deleted.'
            ]);
        return response()->json([
            'message' => 'Cannot remove the item.'
        ], 400);
    }
}
