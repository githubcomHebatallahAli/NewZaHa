<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\Admin\BestCommentController;

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
    Route::get('/show/admin/{id}', [AdminController::class, 'show']);
    Route::get('/edit/admin/{id}', [AdminController::class, 'edit']);
    Route::post('/update/admin/{id}', [AdminController::class, 'update']);
    Route::delete('/softDelete/admin/{id}', [AdminController::class, 'destroy']);
    Route::get('/showDeleted/admin', [AdminController::class, 'showDeleted']);
    Route::get('/restore/admin/{id}', [AdminController::class, 'restore']);
    Route::delete('/forceDelete/admin/{id}', [AdminController::class, 'forceDelete']);

    // CONTACT
    Route::get('/showAll/contact', [ContactController::class, 'showAll']);
    Route::post('/create/contact', [ContactController::class, 'create']);
    Route::get('/show/contact/{id}', [ContactController::class, 'show']);
    Route::get('/edit/contact/{id}', [ContactController::class, 'edit']);
    Route::put('/update/contact/{id}', [ContactController::class, 'update']);
    Route::delete('/softDelete/contact/{id}', [ContactController::class, 'destroy']);
    Route::get('/showDeleted/contact', [ContactController::class, 'showDeleted']);
    Route::get('/restore/contact/{id}', [ContactController::class, 'restore']);
    Route::delete('/forceDelete/contact/{id}', [ContactController::class, 'forceDelete']);

    // COMMENT
    Route::get('/showAll/comment', [CommentController::class, 'showAll']);
    Route::post('/create/comment', [CommentController::class, 'create']);
    Route::get('/show/comment/{id}', [CommentController::class, 'show']);
    Route::get('/edit/comment/{id}', [CommentController::class, 'edit']);
    Route::put('/update/comment/{id}', [CommentController::class, 'update']);
    Route::delete('/softDelete/comment/{id}', [CommentController::class, 'destroy']);
    Route::get('/showDeleted/comment', [CommentController::class, 'showDeleted']);
    Route::get('/restore/comment/{id}', [CommentController::class, 'restore']);
    Route::delete('/forceDelete/comment/{id}', [CommentController::class, 'forceDelete']);

    //BestComment
    Route::get('/showAll/bestComment', [BestCommentController::class, 'showAll']);
    Route::post('/create/bestComment', [BestCommentController::class, 'create']);
    Route::get('/show/bestComment/{id}', [BestCommentController::class, 'show']);
    Route::get('/edit/bestComment/{id}', [BestCommentController::class, 'edit']);
    Route::put('/update/bestComment/{id}', [BestCommentController::class, 'update']);
    Route::delete('/softDelete/bestComment/{id}', [BestCommentController::class, 'destroy']);
    Route::get('/showDeleted/bestComment', [BestCommentController::class, 'showDeleted']);
    Route::get('/restore/bestComment/{id}', [BestCommentController::class, 'restore']);
    Route::delete('/forceDelete/bestComment/{id}', [BestCommentController::class, 'forceDelete']);

    // ORDER
    Route::get('/showAll/order', [OrderController::class, 'showAll']);
    Route::post('/create/order', [OrderController::class, 'create']);
    Route::get('/show/order/{id}', [OrderController::class, 'show']);
    Route::get('/edit/order/{id}', [OrderController::class, 'edit']);
    Route::put('/update/order/{id}', [OrderController::class, 'update']);
    Route::delete('/softDelete/order/{id}', [OrderController::class, 'destroy']);
    Route::get('/showDeleted/order', [OrderController::class, 'showDeleted']);
    Route::get('/restore/order/{id}', [OrderController::class, 'restore']);
    Route::delete('/forceDelete/order/{id}', [OrderController::class, 'forceDelete']);

    // JOB
    Route::get('/showAll/job', [JobController::class, 'showAll']);
    Route::post('/create/job', [JobController::class, 'create']);
    Route::get('/show/job/{id}', [JobController::class, 'show']);
    Route::get('/edit/job/{id}', [JobController::class, 'edit']);
    Route::put('/update/job/{id}', [JobController::class, 'update']);
    Route::delete('/softDelete/job/{id}', [JobController::class, 'destroy']);
    Route::get('/showDeleted/job', [JobController::class, 'showDeleted']);
    Route::get('/restore/job/{id}', [JobController::class, 'restore']);
    Route::delete('/forceDelete/job/{id}', [JobController::class, 'forceDelete']);

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
