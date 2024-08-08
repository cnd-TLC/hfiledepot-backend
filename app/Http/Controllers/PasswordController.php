<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PasswordController extends Controller
{
    public function update_password(Request $request)
    {
        $user = auth()->user();
        if(!Hash::check($request->old_password, $user->password))
            return response()->json([
                'message' => 'Old password doesn\'t match.',
                'status' => 'failed'
            ]);

        if($request->new_password != $request->retype_password)
            return response()->json([
                'message' => 'New password doesn\'t match.',
                'status' => 'failed'
            ]);

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully.',
            'status' => 'success'
        ], 200);
    }
}
