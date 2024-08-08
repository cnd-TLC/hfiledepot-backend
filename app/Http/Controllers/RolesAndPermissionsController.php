<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RolesAndPermissions;

class RolesAndPermissionsController extends Controller
{
    public function all()
    {
        $data = RolesAndPermissions::get();

        return response()->json([
            'retrievedData' => $data
        ]);
    }

    public function index(Request $request, $size)
    {
        $data = RolesAndPermissions::where(function ($query) use ($request) {
                    $query->where('role', 'LIKE', "%$request->search%")
                        ->orWhere('description', 'LIKE', "%$request->search%");
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
        $role_and_permissions = new RolesAndPermissions;
        $role_and_permissions->role = $request->role;
        $role_and_permissions->description = $request->description;
        $role_and_permissions->role_modules = $request->role_modules;
        $result = $role_and_permissions->save();

        if ($result)
            return response()->json([
                'message' => 'Role successfully added.'
            ]);
        return response()->json([
            'message' => 'Cannot add the role.'
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $update_user = User::where('role', $request->role)
                ->update(['permissions' => $request->role_modules]);

        $role_and_permissions = RolesAndPermissions::find($id);
        $role_and_permissions->role = $request->role;
        $role_and_permissions->description = $request->description;
        $role_and_permissions->role_modules = $request->role_modules;
        $result = $role_and_permissions->save();

        if($result)
            return response([
                'message' => 'Role successfully updated.'
            ], 200);
        return response([
            'message' => 'Cannot update the role.'
        ], 400);
    }

    public function destroy($id)
    {
        $role_and_permissions = RolesAndPermissions::find($id);
        $update_user = User::where('role', $role_and_permissions->role)
                ->update(['permissions' => []]);
        $result = $role_and_permissions->delete();

        if($result)
            return response([
                'message' => 'Role successfully removed.'
            ], 200);
        return response([
            'message' => 'Cannot remove the role.'
        ], 400);
    }
}
