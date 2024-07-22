<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseRequest;

class PurchaseRequestController extends Controller
{
    public function index($size)
    {
        $data = [];

        if (auth()->user()->department == 'City Budget Office (CBO)')
            $data = PurchaseRequest::paginate($size);
        if (auth()->user()->department == 'City Treasurer\'s Office (CTO)')
            $data = PurchaseRequest::where('approved_by_cbo_name', '!=', null)->paginate($size);
        if (auth()->user()->department == 'City Mayor\'s Office (CMO)')
            $data = PurchaseRequest::where('approved_by_cto_name', '!=', null)->paginate($size);
        if (auth()->user()->department == 'BAC (BAC)')
            $data = PurchaseRequest::where('approved_by_cmo_name', '!=', null)->paginate($size);
        if (auth()->user()->department == 'City General Services Office (CGSO)')
            $data = PurchaseRequest::where('approved_by_bac_name', '!=', null)->paginate($size);
        if (auth()->user()->department == 'City Accountant\'s Office (CAO)')
            $data = PurchaseRequest::where('approved_by_cgso_name', '!=', null)->paginate($size);

        if($data)
            return response()->json([
                'retrievedData' => $data->items(),
                'currentPage' => $data->currentPage(),
                'perPage' => $data->perPage(),
                'total' => $data->total(),
                'lastPage' => $data->lastPage(),
                'nextPageUrl' => $data->nextPageUrl(),
                'previousPageUrl' => $data->previousPageUrl(),
            ]);
        return response()->json([
            'retrievedData' => [],
            'currentPage' => 1,
            'perPage' => 5,
            'total' => 0,
            'lastPage' => 1,
            'nextPageUrl' => null,
            'previousPageUrl' => null,
        ]);
    }

    public function index_user($size)
    {
        $data = PurchaseRequest::where('department', auth()->user()->department)->paginate($size);

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
        $pr = new PurchaseRequest;
        $pr->department = auth()->user()->department;
        $pr->requested_by = auth()->user()->name;
        $pr->purpose = $request->purpose;
        $result = $pr->save();

        if ($result)
            return response()->json([
                'message' => 'PR successfully added.',
                'id' => $pr->id
            ]);
        return response()->json([
            'message' => 'Cannot add the PR.'
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $pr = PurchaseRequest::find($id);
        $pr->pr_no = $request->pr_no;
        $pr->department = auth()->user()->department;
        $pr->section = $request->section;
        $pr->requested_by = auth()->user()->name;
        $pr->cash_availability = $request->cash_availability;
        $pr->fpp = $request->fpp;
        $pr->fund = $request->fund;
        $pr->status = $request->status;
        $pr->purpose = $request->purpose;
        // $pr->attachments = $request->attachments;
        $result = $pr->save();

        if ($result)
            return response()->json([
                'message' => 'PR successfully updated.',
            ]);
        return response()->json([
            'message' => 'Cannot update the PR.'
        ], 400);
    }

    public function destroy($id)
    {
        $pr = PurchaseRequest::find($id);
        $result = $pr->delete();

        if($result)
            return response([
                'message' => 'PR successfully removed.'
            ], 200);
        return response([
            'message' => 'Cannot remove the PR.'
        ], 400);
    }

    public function set_approval_bac(Request $request, $id)
    {
        $user = null;
        $user = auth()->user()->name;
        $approval = PurchaseRequest::find($id);
        $approval->approved_by_bac_name = $user;
        $approval->pr_no = $request->pr_no;
        $approval->section = $request->section;
        $approval->fund = $request->fund;
        $approval->fpp = $request->fpp;
        // $approval->attachments = $request->attachments
        $result = $approval->save();
        if ($result)
            return response()->json([
                'message' => 'PR Approved.'
            ]);
        return response()->json([
            'message' => 'Cannot configure the PR.'
        ], 400);  
    }

    public function set_approval(Request $request, $id)
    {
        $user = null;
        $status = 'Rejected';
        if ($request->status == 'Approved'){
            $user = auth()->user()->name;
            if(auth()->user()->department != 'City Accountant\'s Office (CAO)')
                $status = 'Pending';
            else
                $status = 'Approved';
        }

        $approval = PurchaseRequest::find($id);
        if (auth()->user()->department == 'City Budget Office (CBO)')
            $approval->approved_by_cbo_name = $user;
        if (auth()->user()->department == 'City Treasurer\'s Office (CTO)')
            $approval->approved_by_cto_name = $user;
        if (auth()->user()->department == 'City Mayor\'s Office (CMO)')
            $approval->approved_by_cmo_name = $user;
        if (auth()->user()->department == 'BAC (BAC)')
            $approval->approved_by_bac_name = $user;
        if (auth()->user()->department == 'City General Services Office (CGSO)')
            $approval->approved_by_cgso_name = $user;
        if (auth()->user()->department == 'City Accountant\'s Office (CAO)')
            $approval->approved_by_cao_name = $user;
        $approval->status = $status;
        $result = $approval->save();

        if ($result)
            return response()->json([
                'message' => 'PR '.$status.'.'
            ]);
        return response()->json([
            'message' => 'Cannot configure the PR.'
        ], 400);   
    }
}
