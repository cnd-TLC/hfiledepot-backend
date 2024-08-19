<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use App\Models\PrItem;
use App\Models\Notification;

class PurchaseRequestController extends Controller
{
    public function convertDate($dateString)
    {
        date_default_timezone_set('Asia/Manila');
        $date = strtotime($dateString);

        return date('Y-m-d H:i:s', $date);
    }

    public function prForMobile($id)
    {
        $data = PurchaseRequest::find($id);

        $data_item_list = PrItem::where('pr_id', $data->id)->get();

        return response()->json([
            'retrievedData' => $data,
            'retrievedItemData' => $data_item_list
        ]);
    }

    public function index(Request $request, $size)
    {
        $data = [];

        $department = auth()->user()->department;

        if ($department == 'City Budget Office (CBO)')
            $data = PurchaseRequest::where(function ($query) use ($request) {
                    $query->where('pr_no', 'LIKE', "%$request->search%")
                        ->orWhere('department', 'LIKE', "%$request->search%")
                        ->orWhere('section', 'LIKE', "%$request->search%")
                        ->orWhere('requested_by', 'LIKE', "%$request->search%")
                        ->orWhere('fpp', 'LIKE', "%$request->search%")
                        ->orWhere('fund', 'LIKE', "%$request->search%")
                        ->orWhere('status', 'LIKE', "%$request->search%");
                    })->paginate($size);
        if ($department == 'City Treasurer\'s Office (CTO)')
            $data = PurchaseRequest::where('approved_by_cbo_name', '!=', null)
                ->where(function ($query) use ($request) {
                    $query->where('pr_no', 'LIKE', "%$request->search%")
                        ->orWhere('department', 'LIKE', "%$request->search%")
                        ->orWhere('section', 'LIKE', "%$request->search%")
                        ->orWhere('requested_by', 'LIKE', "%$request->search%")
                        ->orWhere('fpp', 'LIKE', "%$request->search%")
                        ->orWhere('fund', 'LIKE', "%$request->search%")
                        ->orWhere('status', 'LIKE', "%$request->search%");
                    })
                ->paginate($size);
        if ($department == 'City Mayor\'s Office (CMO)')
            $data = PurchaseRequest::where('approved_by_cto_name', '!=', null)
                ->where(function ($query) use ($request) {
                    $query->where('pr_no', 'LIKE', "%$request->search%")
                        ->orWhere('department', 'LIKE', "%$request->search%")
                        ->orWhere('section', 'LIKE', "%$request->search%")
                        ->orWhere('requested_by', 'LIKE', "%$request->search%")
                        ->orWhere('fpp', 'LIKE', "%$request->search%")
                        ->orWhere('fund', 'LIKE', "%$request->search%")
                        ->orWhere('status', 'LIKE', "%$request->search%");
                    })
                ->paginate($size);
        if ($department == 'Bids and Awards Committee (BAC)')
            $data = PurchaseRequest::where('approved_by_cmo_name', '!=', null)
                ->where(function ($query) use ($request) {
                    $query->where('pr_no', 'LIKE', "%$request->search%")
                        ->orWhere('department', 'LIKE', "%$request->search%")
                        ->orWhere('section', 'LIKE', "%$request->search%")
                        ->orWhere('requested_by', 'LIKE', "%$request->search%")
                        ->orWhere('fpp', 'LIKE', "%$request->search%")
                        ->orWhere('fund', 'LIKE', "%$request->search%")
                        ->orWhere('status', 'LIKE', "%$request->search%");
                    })
                ->paginate($size);
        if ($department == 'City General Services Office (CGSO)')
            $data = PurchaseRequest::where('approved_by_bac_name', '!=', null)
                ->where(function ($query) use ($request) {
                    $query->where('pr_no', 'LIKE', "%$request->search%")
                        ->orWhere('department', 'LIKE', "%$request->search%")
                        ->orWhere('section', 'LIKE', "%$request->search%")
                        ->orWhere('requested_by', 'LIKE', "%$request->search%")
                        ->orWhere('fpp', 'LIKE', "%$request->search%")
                        ->orWhere('fund', 'LIKE', "%$request->search%")
                        ->orWhere('status', 'LIKE', "%$request->search%");
                    })
                ->paginate($size);
        if ($department == 'City Accountant\'s Office (CAccO)')
            $data = PurchaseRequest::where('approved_by_cgso_name', '!=', null)
                ->where(function ($query) use ($request) {
                    $query->where('pr_no', 'LIKE', "%$request->search%")
                        ->orWhere('department', 'LIKE', "%$request->search%")
                        ->orWhere('section', 'LIKE', "%$request->search%")
                        ->orWhere('requested_by', 'LIKE', "%$request->search%")
                        ->orWhere('fpp', 'LIKE', "%$request->search%")
                        ->orWhere('fund', 'LIKE', "%$request->search%")
                        ->orWhere('status', 'LIKE', "%$request->search%");
                    })
                ->paginate($size);

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

    public function index_user(Request $request, $size)
    {
        $data = PurchaseRequest::where('department', auth()->user()->department)
                ->where(function ($query) use ($request) {
                    $query->where('pr_no', 'LIKE', "%$request->search%")
                        ->orWhere('department', 'LIKE', "%$request->search%")
                        ->orWhere('section', 'LIKE', "%$request->search%")
                        ->orWhere('requested_by', 'LIKE', "%$request->search%")
                        ->orWhere('fpp', 'LIKE', "%$request->search%")
                        ->orWhere('fund', 'LIKE', "%$request->search%")
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
        $pr = new PurchaseRequest;
        $pr->department = auth()->user()->department;
        $pr->requested_by = auth()->user()->name;
        $pr->purpose = $request->purpose;
        $result = $pr->save();

        if ($result)
            return response()->json([
                'message' => 'PR successfully added.',
                'id' => $pr->id,
                'department' => $pr->department,
                'requested_by' => $pr->requested_by,
                'status' => 'Pending'
            ]);
        return response()->json([
            'message' => 'Cannot add the PR.'
        ], 400);
    }

    public function update_pr_purpose(Request $request, $id)
    {
        $pr = PurchaseRequest::find($id);
        $pr->purpose = $request->purpose;
        $result = $pr->save();

        if ($result)
            return response()->json([
                'message' => 'PR successfully updated.',
            ]);
        return response()->json([
            'message' => 'Cannot update the PR.'
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $pr = PurchaseRequest::find($id);
        $pr->purpose = $request->purpose;
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

    public function set_pr_details(Request $request, $id)
    {
        $notification_sender = auth()->user()->name;
        $approval_user = $notification_sender;
        $department = auth()->user()->department;
        $purchase_request = PurchaseRequest::find($id);
        $purchase_request->pr_no = $request->pr_no;
        $purchase_request->ris_no = $request->ris_no;
        $purchase_request->air_no = $request->air_no;
        $purchase_request->ics_no = $request->ics_no;
        $purchase_request->insp_no = $request->insp_no;
        $purchase_request->section = $request->section;
        $purchase_request->fund = $request->fund;
        $purchase_request->fpp = $request->fpp;
        $purchase_request->bac_resolution = $request->bac_resolution == true ? $this->convertDate($request->bac_resolution_date) : null;
        $purchase_request->canvass = $request->canvass == true ? $this->convertDate($request->canvass_date) : null;
        $purchase_request->purchase_order = $request->purchase_order == true ? $this->convertDate($request->purchase_order_date) : null;
        $purchase_request->obr = $request->obr == true ? $this->convertDate($request->obr_date) : null;
        $purchase_request->ris = $request->ris == true ? $this->convertDate($request->ris_date) : null;
        $purchase_request->inspection_acceptance = $request->inspection_acceptance == true ? $this->convertDate($request->inspection_acceptance_date) : null;
        $purchase_request->abstract = $request->abstract == true ? $this->convertDate($request->abstract_date) : null;
        $purchase_request->voucher = $request->voucher == true ? $this->convertDate($request->voucher_date) : null;
        $purchase_request->notice_of_awards = $request->notice_of_awards == true ? $this->convertDate($request->notice_of_awards_date) : null;
        $purchase_request->notice_to_proceed = $request->notice_to_proceed == true ? $this->convertDate($request->notice_to_proceed_date) : null;
        $purchase_request->contract_of_agreement = $request->contract_of_agreement == true ? $this->convertDate($request->contract_of_agreement_date) : null;
        $purchase_request->lcrb = $request->lcrb == true ? $this->convertDate($request->lcrb_date) : null;
        $result = $purchase_request->save();

        $notification = new Notification;
        $notification->sender = $notification_sender;
        $notification->sender_department = $department;
        $notification->receiver_department = $purchase_request->department;
        $notification->message = $request->status == 'Updated' ? 'Your purchase request has been updated by ' . $notification_sender . ' from ' . $department . '.' : 'Your purchase request has been rejected by ' . $notification_sender . ' from ' . $department . '.';
        $notification->save();
        
        if ($result)
            return response()->json([
                'message' => 'PR Updated.'
            ]);
        return response()->json([
            'message' => 'Cannot configure the PR.'
        ], 400);  
    }

    public function set_approval(Request $request, $id)
    {
        $reason = null;
        if ($request->reason)
            $reason = $request->reason;

        $approval_user = null;
        $notification_sender = auth()->user()->name;
        $department = auth()->user()->department;
        $status = 'Rejected';

        if ($request->status == 'Approved'){
            $approval_user = $notification_sender;
            if($department != 'City Accountant\'s Office (CAccO)')
                $status = 'Pending';
            else
                $status = 'Approved';
        }

        $purchase_request = PurchaseRequest::find($id);
        if ($department == 'City Budget Office (CBO)'){
            $purchase_request->approved_by_cbo = date('Y-m-d H:i:s');
            $purchase_request->approved_by_cbo_name = $approval_user;
        }
        if ($department == 'City Treasurer\'s Office (CTO)'){
            $purchase_request->approved_by_cto = date('Y-m-d H:i:s');
            $purchase_request->approved_by_cto_name = $approval_user;
        }
        if ($department == 'City Mayor\'s Office (CMO)'){
            $purchase_request->approved_by_cmo = date('Y-m-d H:i:s');
            $purchase_request->approved_by_cmo_name = $approval_user;
        }
        if ($department == 'Bids and Awards Committee (BAC)'){
            $purchase_request->approved_by_bac = date('Y-m-d H:i:s');
            $purchase_request->approved_by_bac_name = $approval_user;
        }
        if ($department == 'City General Services Office (CGSO)'){
            $purchase_request->approved_by_cgso = date('Y-m-d H:i:s');
            $purchase_request->approved_by_cgso_name = $approval_user;
        }
        if ($department == 'City Accountant\'s Office (CAccO)'){
            $purchase_request->approved_by_cao = date('Y-m-d H:i:s');
            $purchase_request->approved_by_cao_name = $approval_user;
        }
        $purchase_request->status = $status;
        $purchase_request->remarks = $reason;
        $result = $purchase_request->save();


        $notification = new Notification;
        $notification->sender = $notification_sender;
        $notification->sender_department = $department;
        $notification->receiver_department = $purchase_request->department;
        $notification->message = $request->status == 'Approved' ? 'Your purchase request has been approved by ' . $notification_sender . ' from ' . $department . '.' : 'Your purchase request has been rejected by ' . $notification_sender . ' from ' . $department . '.';
        $notification->save();

        if ($result)
            return response()->json([
                'message' => 'PR Approved.'
            ]);
        return response()->json([
            'message' => 'Cannot configure the PR.'
        ], 400);   
    }
}
