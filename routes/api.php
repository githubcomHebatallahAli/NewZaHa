<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'resetPassword'
], function () {
    Route::post('/sendEmail', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('forgot.password'); ;
    Route::post('/reset', [ResetPasswordController::class, 'reset']);
});
// Route::get('auth/google',[SocialiteController::class,'redirectToGoogle'] )->name('auth.google');

// Route::get('auth/google/callback',[SocialiteController::class,'handleGoogleCallback']);

Route::group([
    'middleware' => 'admin',
    'prefix' => 'admin'
], function () {
    // Admin
    Route::get('/showAll/admin', [AdminController::class, 'showAll']);
    Route::post('/create/admin', [AdminController::class, 'create']);
    Route::get('/showAll/admin/{id}', [AdminController::class, 'show']);
    Route::get('/edit/admin/{id}', [AdminController::class, 'edit']);
    Route::put('/update/admin/{id}', [AdminController::class, 'update']);
    Route::delete('/softDelete/admin/{id}', [AdminController::class, 'destroy']);
    Route::get('/showDeleted/admin', [AdminController::class, 'showDeleted']);
    Route::get('/restore/admin/{id}', [AdminController::class, 'restore']);
    Route::delete('/forceDelete/admin/{id}', [AdminController::class, 'forceDelete']);

    // CONTACT
    Route::get('/showAll/contact', [AdminController::class, 'showAll']);
    Route::post('/create/contact', [AdminController::class, 'create']);
    Route::get('/showAll/contact/{id}', [AdminController::class, 'show']);
    Route::get('/edit/contact/{id}', [AdminController::class, 'edit']);
    Route::put('/update/contact/{id}', [AdminController::class, 'update']);
    Route::delete('/softDelete/contact/{id}', [AdminController::class, 'destroy']);
    Route::get('/showDeleted/contact', [AdminController::class, 'showDeleted']);
    Route::get('/restore/contact/{id}', [AdminController::class, 'restore']);
    Route::delete('/forceDelete/contact/{id}', [AdminController::class, 'forceDelete']);
    //ROLE&PERMISSION
    Route::post('/create/role', [RolesAndPermissionsController::class, 'createRole']);
    Route::get('/edit/role/{id}', [RolesAndPermissionsController::class, 'editRole']);
    Route::put('/update/role/{id}', [RolesAndPermissionsController::class, 'updateRole']);
    Route::delete('/delete/role/{id}', [RolesAndPermissionsController::class, 'deleteRole']);
    Route::post('/create/permission', [RolesAndPermissionsController::class, 'createPermission']);
    Route::get('/edit/permission/{id}', [RolesAndPermissionsController::class, 'editPermission']);
    Route::put('/update/permission/{id}', [RolesAndPermissionsController::class, 'updatePermission']);
    Route::delete('/delete/permission/{id}', [RolesAndPermissionsController::class, 'deletePermission']);
    Route::get('/showAll/permissions', [RolesAndPermissionsController::class, 'showAllPermissions']);
    Route::post('/assign/role/{roleId}/to/permission/{permissionId}', [RolesAndPermissionsController::class, 'assignRoleToPermission']);
    Route::delete('/revoke/role/{roleId}/from/permission/{permissionId}', [RolesAndPermissionsController::class, 'unassignRoleToPermission']);
    Route::get('/showAll/rolesWithPermissions', [RolesAndPermissionsController::class, 'showAll']);
    Route::post('admin/assign/role/{roleId}/to/user/{userId}', [RolesAndPermissionsController::class, 'assignRoleToUser']);
    Route::delete('/revoke/role/{roleId}/from/user/{userId}', [RolesAndPermissionsController::class, 'revokeRoleFromUser']);
    Route::post('/assign/permission/{permissionId}/to/user/{userId}', [RolesAndPermissionsController::class, 'assignPermissionToUser']);
    Route::delete('/revoke/permission/{permissionId}/from/user/{userId}', [RolesAndPermissionsController::class, 'revokePermissionFromUser']);





});
