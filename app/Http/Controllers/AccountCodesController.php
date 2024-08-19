<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartOfAccount;

class AccountCodesController extends Controller
{
    public function index()
    {
        $data = ChartOfAccount::get();

        return response()->json([
            'retrievedData' => $data
        ]);
    }
}
