<?php

namespace App\Http\Controllers;
use App\Models\PpmpItem;

use Illuminate\Http\Request;

class PpmpItemController extends Controller
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

        return response()->json(['code' => $setCode]);
    }

    public function index($id, $size)
    {
        $data = PpmpItem::where('ppmp_id', $id)->paginate($size);

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
        $item = new PpmpItem;
        $item->code = $request->code;
        $item->category = $request->category;
        $item->general_desc = $request->general_desc;
        $item->unit = $request->lumpsum ? null : $request->unit;
        $item->quantity = $request->quantity;
        $item->lumpsum = $request->lumpsum;
        $item->mode_of_procurement = $request->mode_of_procurement;
        $item->estimated_budget = $request->estimated_budget;
        $item->jan = $request->jan;
        $item->feb = $request->feb;
        $item->mar = $request->mar;
        $item->apr = $request->apr;
        $item->may = $request->may;
        $item->jun = $request->jun;
        $item->jul = $request->jul;
        $item->aug = $request->aug;
        $item->sept = $request->sept;
        $item->oct = $request->oct;
        $item->nov = $request->nov;
        $item->dec = $request->dec;
        $item->ppmp_id = $request->ppmp_id;
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
        $item = PpmpItem::find($id);
        $item->code = $request->code;
        $item->category = $request->category;
        $item->general_desc = $request->general_desc;
        $item->unit = $request->lumpsum ? null : $request->unit;
        $item->quantity = $request->quantity;
        $item->lumpsum = $request->lumpsum;
        $item->mode_of_procurement = $request->mode_of_procurement;
        $item->estimated_budget = $request->estimated_budget;
        $item->jan = $request->jan;
        $item->feb = $request->feb;
        $item->mar = $request->mar;
        $item->apr = $request->apr;
        $item->may = $request->may;
        $item->jun = $request->jun;
        $item->jul = $request->jul;
        $item->aug = $request->aug;
        $item->sept = $request->sept;
        $item->oct = $request->oct;
        $item->nov = $request->nov;
        $item->dec = $request->dec;
        $item->ppmp_id = $request->ppmp_id;
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
        $item = PpmpItem::find($id);
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
