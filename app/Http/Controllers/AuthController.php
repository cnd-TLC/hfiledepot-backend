<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Session;

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

        if($user->status == 'Inactive'){
            return response([
                'message' => ['This account is not activated.']
            ], 401);
        }

        $token = $user->createToken('personal-token')->plainTextToken;

        $deviceName = $request->header('User-Agent');
        $existingSession = Session::where('user_id', $user->id)
                                ->where('device_name', $deviceName)
                                ->first();

        if ($existingSession)
            return response()
                ->json(compact('token', 'user'))
                ->header('authorization', $token)
                ->header('Access-Control-Expose-Headers', 'Authorization');

        $session = new Session;
        $session->user_id = $user->id;
        $session->device_name = $request->header('User-Agent');
        $session->ip_address = $request->ip();
        $session->user_agent = $request->header('User-Agent');
        $session->session_id = session()->getId();
        $result = $session->save();

        return response()
                ->json(compact('token', 'user'))
                ->header('authorization', $token)
                ->header('Access-Control-Expose-Headers', 'Authorization');
    }

    public function logout(Request $request)
    {
        if(auth()->user())
            auth()->user()->currentAccessToken()->delete();

        return response([
            'message' => 'Logged out.'
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

    public function logout_all(Request $request)
    {
        if(auth()->user()){
            auth()->user()->sessions()->delete();
            auth()->user()->tokens()->delete();
        }

        return response([
            'message' => 'Logged out all devices.'
        ], 200);
    }

    public function active_sessions()
    {
        $sessions = auth()->user()->sessions;

        return response()->json(['retrievedData' => $sessions]);
    }
}
