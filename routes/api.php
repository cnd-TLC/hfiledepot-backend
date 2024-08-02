<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolesAndPermissionsController;
use App\Http\Controllers\PpmpItemsCatalogController;
use App\Http\Controllers\ProcurementProjectManagementPlanController;
use App\Http\Controllers\PpmpItemController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\PrItemController;
use App\Http\Controllers\AttachmentsController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\DashboardController;

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

// LOGOUT
Route::post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function(){
    
    // LOGIN INFO
    Route::get('user', [AuthController::class, 'user']);
    Route::get('refresh', [AuthController::class, 'refresh']);

    Route::get('list_of_users/{size}', [UserController::class, 'index']);
    Route::post('add_users', [UserController::class, 'store']);
    Route::put('update_users/{id}', [UserController::class, 'update']);
    Route::delete('remove_users/{id}', [UserController::class, 'destroy']);

    Route::get('list_of_all_roles_and_permissions', [RolesAndPermissionsController::class, 'all']);
    Route::get('list_of_roles_and_permissions/{size}', [RolesAndPermissionsController::class, 'index']);
    Route::post('add_roles_and_permissions', [RolesAndPermissionsController::class, 'store']);
    Route::put('update_roles_and_permissions/{id}', [RolesAndPermissionsController::class, 'update']);
    Route::delete('remove_roles_and_permissions/{id}', [RolesAndPermissionsController::class, 'destroy']);

    Route::get('list_of_department_ppmp_items_catalog', [PpmpItemsCatalogController::class, 'index_department']);
    Route::get('list_of_ppmp_items_catalog/{size}', [PpmpItemsCatalogController::class, 'index']);
    Route::post('add_ppmp_items_catalog', [PpmpItemsCatalogController::class, 'store']);
    Route::put('update_ppmp_items_catalog/{id}', [PpmpItemsCatalogController::class, 'update']);
    Route::delete('remove_ppmp_items_catalog/{id}', [PpmpItemsCatalogController::class, 'destroy']);

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

    Route::get('list_of_pr/{size}', [PurchaseRequestController::class, 'index']);
    Route::get('list_of_user_pr/{size}', [PurchaseRequestController::class, 'index_user']);
    Route::post('add_pr', [PurchaseRequestController::class, 'store']);
    Route::put('update_pr/{id}', [PurchaseRequestController::class, 'update']);
    // Route::put('update_pr_purpose/{id}', [PurchaseRequestController::class, 'update_pr_purpose']);
    Route::put('set_approval_pr_bac/{id}', [PurchaseRequestController::class, 'set_approval_bac']);
    Route::put('set_approval_pr/{id}', [PurchaseRequestController::class, 'set_approval']);
    Route::delete('remove_pr/{id}', [PurchaseRequestController::class, 'destroy']);

    Route::get('list_of_department_ppmp_items', [PrItemController::class, 'index_department']);
    Route::get('list_of_pr_items/{id}/{size}', [PrItemController::class, 'index']);
    Route::post('add_pr_items', [PrItemController::class, 'store']);
    Route::put('update_pr_items/{id}', [PrItemController::class, 'update']);
    Route::delete('remove_pr_items/{id}', [PrItemController::class, 'destroy']);

    Route::post('add_attachments/{type}/{id}', [AttachmentsController::class, 'store']);
    Route::get('get_attachments/{type}/{id}', [AttachmentsController::class, 'index']);
    Route::get('download_attachment/{type}/{id}/{file}', [AttachmentsController::class, 'download']);
    Route::delete('remove_attachment/{type}/{id}/{file}', [AttachmentsController::class, 'destroy']);

    Route::get('export_users', [ExportController::class, 'export_users']);
    Route::post('import_files/{type}/{id}', [ImportController::class, 'import_files']);

    Route::get('requests_for_approval/{year}', [DashboardController::class, 'getPendingCount']);
    Route::get('requests_per_office/{year}', [DashboardController::class, 'getOfficeRequestsCount']);
    Route::get('department_requests_count/{status}/{year}', [DashboardController::class, 'getDepartmentRequestsStatusCount']);

});