<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@sample',
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'department' => 'City Information and Communications Technology Office (CICTO)',
            'role' => 'Admin',
            'permissions' => json_encode(["purchaseOrdersHasView", "totalSpendOnProcurementHasView", "purchaseRequestHasView", "purchaseRequestHasSend", "purchaseRequestHasUpdate", "purchaseRequestHasRemove", "purchaseRequestHasRequestItems", "purchaseRequestApprovalHasView", "purchaseRequestApprovalHasGeneralApprove", "purchaseRequestApprovalHasUpdateDetails", "purchaseRequestApprovalHasReject", "managePpmpHasView", "managePpmpHasAdd", "managePpmpHasUpdate", "managePpmpHasRemove", "managePpmpHasManageItems", "ppmpApprovalHasView", "ppmpApprovalHasApprove", "ppmpApprovalHasReject", "ppmpItemsMasterListHasView", "systemUsersHasView", "systemUsersHasAdd", "systemUsersHasUpdate", "systemUsersHasRemove", "rolesAndPermissionsHasView", "rolesAndPermissionsHasAdd", "rolesAndPermissionsHasUpdate", "rolesAndPermissionsHasRemove", "purchaseRequestHasManageAttachments", "purchaseRequestApprovalHasManageAttachments", "managePpmpHasManageAttachments", "ppmpApprovalHasManageAttachments"]),
            'status' => 'Active',
        ]);
    }
}
