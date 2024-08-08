<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcurementProjectManagementPlan;
use App\Models\Notification;

class ProcurementProjectManagementPlanController extends Controller
{
    public function index(Request $request, $size)
    {
        $data = ProcurementProjectManagementPlan::where(function ($query) use ($request) {
                    $query->where('year', 'LIKE', "%$request->search%")
                        ->orWhere('title', 'LIKE', "%$request->search%")
                        ->orWhere('pmo_end_user_dept', 'LIKE', "%$request->search%")
                        ->orWhere('source_of_funds', 'LIKE', "%$request->search%")
                        ->orWhere('status', 'LIKE', "%$request->search%");
                    })
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

    public function index_user(Request $request, $size)
    {
        $data = ProcurementProjectManagementPlan::where('pmo_end_user_dept', auth()->user()->department)
                ->where(function ($query) use ($request) {
                    $query->where('year', 'LIKE', "%$request->search%")
                        ->orWhere('title', 'LIKE', "%$request->search%")
                        ->orWhere('pmo_end_user_dept', 'LIKE', "%$request->search%")
                        ->orWhere('source_of_funds', 'LIKE', "%$request->search%")
                        ->orWhere('status', 'LIKE', "%$request->search%");
                    })
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
        $department = auth()->user()->department;
        $notification_sender = auth()->user()->name;
        if ($request->reason)
            $reason = $request->reason;

        $ppmp = ProcurementProjectManagementPlan::find($id);
        $ppmp->status = $request->status;
        $ppmp->remarks = $reason;
        $result = $ppmp->save();


        $notification = new Notification;
        $notification->sender = $notification_sender;
        $notification->sender_department = $department;
        $notification->receiver_department = $ppmp->pmo_end_user_dept;
        $notification->message = $request->status == 'Approved' ? 'Your procurement project management plan has been approved by ' . $notification_sender . ' from ' . $department . '.' : 'Your procurement project management plan has been rejected by ' . $notification_sender . ' from ' . $department . '.';
        $notification->save();

        if ($result)
            return response()->json([
                'message' => 'PPMP '.$request->status.'.'
            ]);
        return response()->json([
            'message' => 'Cannot configure the PPMP.'
        ], 400);
    }
}
