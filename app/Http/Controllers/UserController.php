<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RolesAndPermissions;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index($size)
    {
        $data = User::select('id', 'name', 'email', 'username', 'department', 'role', 'status')->where('id', '!=', auth()->user()->id)->paginate($size);

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
        $permission = RolesAndPermissions::where('role', $request->role)->get();

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->department = $request->department;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->permissions = $permission[0]->role_modules;
        $result = $user->save();

        if ($result)
            return response()->json([
                'message' => 'User successfully added.'
            ]);
        return response()->json([
            'message' => $result
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $permission = RolesAndPermissions::where('role', $request->role)->get();

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->department = $request->department;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->permissions = $permission[0]->role_modules;
        $result = $user->save();

        if ($result)
            return response()->json([
                'message' => 'User successfully updated.'
            ]);
        return response()->json([
            'message' => 'Cannot update the user.'
        ], 400);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $result = $user->delete();

        if($result)
            return response([
                'message' => 'User successfully removed.'
            ], 200);
        return response([
            'message' => 'Cannot remove the user.'
        ], 400);
    }
}
