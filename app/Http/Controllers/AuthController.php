<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('users.username', $request->username)
                    ->first();
        
        if(!$user || !Hash::check($request->password, $user->password)){
            return response([
                'message' => ['Credentials doesn\'t match any records.']
            ], 401);
        }

        $token = $user->createToken('personal-token')->plainTextToken;

        return response()
                ->json(compact('token', 'user'))
                ->header('authorization', $token)
                ->header('Access-Control-Expose-Headers', 'Authorization');
    }

    public function logout(Request $request)
    {
        if(auth()->user())
            auth()->user()->tokens()->delete();

        return response([
            'message' => ['Logged out.']
        ], 200);
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        $token = $user->createToken('personal-token')->plainTextToken;

        return response()
                ->json(compact('token', 'user'))
                ->header('authorization', $token)
                ->header('Access-Control-Expose-Headers', 'Authorization');
    }

    public function user()
    {
        $user = User::find(auth()->id());
        return response([$user], 200);
    }
}
