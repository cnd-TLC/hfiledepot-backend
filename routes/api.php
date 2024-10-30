<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolesAndPermissionsController;
use App\Http\Controllers\PpmpItemsMasterListController;
use App\Http\Controllers\ProcurementProjectManagementPlanController;
use App\Http\Controllers\PpmpItemController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\PrItemController;
use App\Http\Controllers\AttachmentsController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\AccountCodesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// LOGIN
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function(){
    // LOGOUT
    Route::post('logout', [AuthController::class, 'logout']);
    
    // LOGIN INFO
    Route::post('logout_all', [AuthController::class, 'logout_all']);
    Route::get('user', [AuthController::class, 'user']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::get('active_sessions', [AuthController::class, 'active_sessions']);

    Route::put('update_password', [PasswordController::class, 'update_password']);

    Route::get('list_of_users/{size}', [UserController::class, 'index']);
    Route::get('list_of_online_users/{size}', [UserController::class, 'online_users']);
    Route::post('add_users', [UserController::class, 'store']);
    Route::put('update_users/{id}', [UserController::class, 'update']);
    Route::delete('remove_users/{id}', [UserController::class, 'destroy']);

    Route::get('list_of_all_roles_and_permissions', [RolesAndPermissionsController::class, 'all']);
    Route::get('list_of_roles_and_permissions/{size}', [RolesAndPermissionsController::class, 'index']);
    Route::post('add_roles_and_permissions', [RolesAndPermissionsController::class, 'store']);
    Route::put('update_roles_and_permissions/{id}', [RolesAndPermissionsController::class, 'update']);
    Route::delete('remove_roles_and_permissions/{id}', [RolesAndPermissionsController::class, 'destroy']);

    // Route::get('list_of_department_ppmp_items_catalog', [PpmpItemsCatalogController::class, 'index_department']);
    // Route::get('list_of_ppmp_items_catalog/{size}', [PpmpItemsCatalogController::class, 'index']);
    // Route::post('add_ppmp_items_catalog', [PpmpItemsCatalogController::class, 'store']);
    // Route::put('update_ppmp_items_catalog/{id}', [PpmpItemsCatalogController::class, 'update']);
    // Route::delete('remove_ppmp_items_catalog/{id}', [PpmpItemsCatalogController::class, 'destroy']);

    Route::get('list_of_ppmp_master_list/{size}', [PpmpItemsMasterListController::class, 'index']);

    Route::get('list_of_ppmp/{size}', [ProcurementProjectManagementPlanController::class, 'index']);
    Route::get('list_of_user_ppmp/{size}', [ProcurementProjectManagementPlanController::class, 'index_user']);
    Route::post('add_ppmp', [ProcurementProjectManagementPlanController::class, 'store']);
    Route::put('update_ppmp/{id}', [ProcurementProjectManagementPlanController::class, 'update']);
    Route::put('set_approval_ppmp/{id}', [ProcurementProjectManagementPlanController::class, 'set_approval']);
    Route::delete('remove_ppmp/{id}', [ProcurementProjectManagementPlanController::class, 'destroy']);

    Route::get('get_code/{id}', [PpmpItemController::class, 'code']);
    Route::get('list_of_ppmp_items/{id}/{size}', [PpmpItemController::class, 'index']);
    Route::post('add_ppmp_items', [PpmpItemController::class, 'store']);
    Route::put('update_ppmp_items/{id}', [PpmpItemController::class, 'update']);
    Route::delete('remove_ppmp_items/{id}', [PpmpItemController::class, 'destroy']);

    Route::get('get_pr/{id}', [PurchaseRequestController::class, 'prForMobile']);
    Route::get('list_of_pr/{size}', [PurchaseRequestController::class, 'index']);
    Route::get('list_of_user_pr/{size}', [PurchaseRequestController::class, 'index_user']);
    Route::post('add_pr', [PurchaseRequestController::class, 'store']);
    Route::put('update_pr/{id}', [PurchaseRequestController::class, 'update']);
    // Route::put('update_pr_purpose/{id}', [PurchaseRequestController::class, 'update_pr_purpose']);
    Route::put('set_pr_details/{id}', [PurchaseRequestController::class, 'set_pr_details']);
    Route::put('set_approval_pr/{id}', [PurchaseRequestController::class, 'set_approval']);
    Route::delete('remove_pr/{id}', [PurchaseRequestController::class, 'destroy']);
    Route::post('ping/{id}/{cbo}/{cto}/{cmo}/{bac}/{cgso}/{cao}', [PurchaseRequestController::class, 'ping']);

    Route::get('list_of_department_ppmp_items', [PrItemController::class, 'index_department']);
    Route::get('list_of_pr_items/{id}/{size}', [PrItemController::class, 'index']);
    Route::post('add_pr_items', [PrItemController::class, 'store']);
    Route::put('update_pr_items/{id}', [PrItemController::class, 'update']);
    Route::delete('remove_pr_items/{id}', [PrItemController::class, 'destroy']);

    Route::post('add_attachments/{type}/{id}', [AttachmentsController::class, 'store']);
    Route::get('get_attachments/{type}/{id}', [AttachmentsController::class, 'index']);
    Route::get('download_attachment/{type}/{id}/{file}', [AttachmentsController::class, 'download']);
    Route::delete('remove_attachment/{type}/{id}/{file}', [AttachmentsController::class, 'destroy']);

    Route::get('export_files/{type}', [ExportController::class, 'export_files']);
    Route::post('import_files/{type}/{id}', [ImportController::class, 'import_files']);

    Route::get('requests_for_approval/{year}', [DashboardController::class, 'getPendingCount']);
    Route::get('requests_per_office/{year}', [DashboardController::class, 'getOfficeRequestsCount']);
    Route::get('department_requests_count/{status}/{year}', [DashboardController::class, 'getDepartmentRequestsStatusCount']);
    Route::get('notifications/{year}', [DashboardController::class, 'getNotifications']);

    Route::get('list_of_account_codes', [AccountCodesController::class, 'index']);

});