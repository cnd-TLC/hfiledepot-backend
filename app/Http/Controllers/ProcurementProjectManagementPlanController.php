<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcurementProjectManagementPlan;

class ProcurementProjectManagementPlanController extends Controller
{
    public function index($size)
    {
        $data = ProcurementProjectManagementPlan::paginate($size);

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

    public function index_user($size)
    {
        $data = ProcurementProjectManagementPlan::where('pmo_end_user_dept', auth()->user()->department)->paginate($size);

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
        $ppmp = new ProcurementProjectManagementPlan;
        $ppmp->year = $request->year;
        $ppmp->title = $request->title;
        $ppmp->pmo_end_user_dept = $request->pmo_end_user_dept;
        $ppmp->source_of_funds = $request->source_of_funds;
        // $ppmp->attachments = $request->attachments;
        $result = $ppmp->save();

        if ($result)
            return response()->json([
                'message' => 'PPMP successfully added.',
                'id' => $ppmp->id
            ]);
        return response()->json([
            'message' => 'Cannot add the PPMP.'
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $ppmp = ProcurementProjectManagementPlan::find($id);
        $ppmp->year = $request->year;
        $ppmp->title = $request->title;
        $ppmp->pmo_end_user_dept = $request->pmo_end_user_dept;
        $ppmp->source_of_funds = $request->source_of_funds;
        // $ppmp->attachments = $request->attachments;
        $result = $ppmp->save();

        if ($result)
            return response()->json([
                'message' => 'PPMP successfully updated.'
            ]);
        return response()->json([
            'message' => 'Cannot update the PPMP.'
        ], 400);
    }

    public function destroy($id)
    {
        $ppmp = ProcurementProjectManagementPlan::find($id);
        $result = $ppmp->delete();

        if($result)
            return response([
                'message' => 'PPMP successfully removed.'
            ], 200);
        return response([
            'message' => 'Cannot remove the PPMP.'
        ], 400);
    }

    public function set_approval(Request $request, $id)
    {
        $reason = null;
        if ($request->reason)
            $reason = $request->reason;

        $ppmp = ProcurementProjectManagementPlan::find($id);
        $ppmp->status = $request->status;
        $ppmp->remarks = $reason;
        $result = $ppmp->save();

        if ($result)
            return response()->json([
                'message' => 'PPMP '.$request->status.'.'
            ]);
        return response()->json([
            'message' => 'Cannot configure the PPMP.'
        ], 400);
    }
}
