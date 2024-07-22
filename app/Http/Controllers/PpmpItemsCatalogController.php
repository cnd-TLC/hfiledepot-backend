<?php

namespace App\Http\Controllers;
use App\Models\PpmpItemsCatalog;

use Illuminate\Http\Request;

class PpmpItemsCatalogController extends Controller
{
    public function index_department()
    {
        $data = PpmpItemsCatalog::where('department', auth()->user()->department)->where('year', date('Y'))->get();

        return response()->json([
            'retrievedData' => $data
        ]);
    }
    
    public function index($size)
    {
        $data = PpmpItemsCatalog::paginate($size);

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
        $item = new PpmpItemsCatalog;
        $item->general_desc = $request->general_desc;
        $item->unit = $request->unit;
        // $item->mode_of_procurement = $request->mode_of_procurement;
        $item->year = $request->year;
        $item->department = $request->department;
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
        $item = PpmpItemsCatalog::find($id);
        $item->general_desc = $request->general_desc;
        $item->unit = $request->unit;
        // $item->mode_of_procurement = $request->mode_of_procurement;
        $item->year = $request->year;
        $item->department = $request->department;
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
        $item = PpmpItemsCatalog::find($id);
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
