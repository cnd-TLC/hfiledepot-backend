<?php

namespace Database\Seeders;

use App\Models\RolesAndPermissions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        RolesAndPermissions::create([
            'role' => 'Admin',
            'description' => 'Manages the whole system modules',
            'role_modules' => json_encode(["purchaseOrdersHasView", "totalSpendOnProcurementHasView", "purchaseRequestHasView", "purchaseRequestHasSend", "purchaseRequestHasUpdate", "purchaseRequestHasRemove", "purchaseRequestHasRequestItems", "purchaseRequestHasManageAttachments", "purchaseRequestApprovalHasView", "purchaseRequestApprovalHasGeneralApprove", "purchaseRequestApprovalHasBacApprove", "purchaseRequestApprovalHasManageAttachments","purchaseRequestApprovalHasUpdate", "purchaseRequestApprovalHasReject", "managePpmpHasView", "managePpmpHasAdd", "managePpmpHasUpdate", "managePpmpHasRemove", "managePpmpHasManageItems", "managePpmpHasManageAttachments", "ppmpApprovalHasView", "ppmpApprovalHasApprove", "ppmpApprovalHasReject", "ppmpApprovalHasManageAttachments", "ppmpItemsCatalogHasView", "ppmpItemsCatalogHasAdd", "ppmpItemsCatalogHasUpdate", "ppmpItemsCatalogHasRemove", "systemUsersHasView", "systemUsersHasAdd", "systemUsersHasUpdate", "systemUsersHasRemove", "rolesAndPermissionsHasView", "rolesAndPermissionsHasAdd", "rolesAndPermissionsHasUpdate", "rolesAndPermissionsHasRemove", "purchaseRequestHasManageAttachments", "purchaseRequestApprovalHasManageAttachments", "managePpmpHasManageAttachments", "ppmpApprovalHasManageAttachments"]),
        ]);
        RolesAndPermissions::create([
            'role' => 'Budget Head',
            'description' => 'Manages CBO modules',
            'role_modules' => json_encode([])
        ]);
        RolesAndPermissions::create([
            'role' => 'Treasury Head',
            'description' => 'Manages CTO modules',
            'role_modules' => json_encode([])
        ]);
        RolesAndPermissions::create([
            'role' => 'CGSO Head',
            'description' => 'Manages CGSO modules',
            'role_modules' => json_encode([])
        ]);
        RolesAndPermissions::create([
            'role' => 'BAC Head',
            'description' => 'Manages BAC modules',
            'role_modules' => json_encode([])
        ]);
        RolesAndPermissions::create([
            'role' => 'CICTO Head',
            'description' => 'Manages CICTO modules',
            'role_modules' => json_encode([])
        ]);
        RolesAndPermissions::create([
            'role' => 'CMO Head',
            'description' => 'Manages CMO modules',
            'role_modules' => json_encode([])
        ]);
        RolesAndPermissions::create([
            'role' => 'Accounting Head',
            'description' => 'Manages Accounting Office modules',
            'role_modules' => json_encode([])
        ]);
    }
}
