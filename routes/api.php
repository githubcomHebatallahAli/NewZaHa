<?php

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\User\JobUserController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\User\OrderUserController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\User\CommentUserController;
use App\Http\Controllers\User\ContactUserController;
use App\Http\Controllers\Admin\BestCommentController;
use App\Http\Controllers\Admin\RolesAndPermissionsController;


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
Route::get('auth/google',[SocialiteController::class,'redirectToGoogle'] )->name('auth.google');

Route::get('auth/google/callback',[SocialiteController::class,'handleGoogleCallback']);


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

    // TEAM
    Route::get('/showAll/team', [TeamController::class, 'showAll']);
    Route::post('/create/team', [TeamController::class, 'create']);
    Route::get('/show/team/{id}', [TeamController::class, 'show']);
    Route::get('/edit/team/{id}', [TeamController::class, 'edit']);
    Route::post('/update/team/{id}', [TeamController::class, 'update']);
    Route::delete('/softDelete/team/{id}', [TeamController::class, 'destroy']);
    Route::get('/showDeleted/team', [TeamController::class, 'showDeleted']);
    Route::get('/restore/team/{id}', [TeamController::class, 'restore']);
    Route::delete('/forceDelete/team/{id}', [TeamController::class, 'forceDelete']);

    // CLIENT
    Route::get('/showAll/client', [ClientController::class, 'showAll']);
    Route::post('/create/client', [ClientController::class, 'create']);
    Route::get('/show/client/{id}', [ClientController::class, 'show']);
    Route::get('/edit/client/{id}', [ClientController::class, 'edit']);
    Route::post('/update/client/{id}', [ClientController::class, 'update']);
    Route::delete('/softDelete/client/{id}', [clientController::class, 'destroy']);
    Route::get('/showDeleted/client', [ClientController::class, 'showDeleted']);
    Route::get('/restore/client/{id}', [ClientController::class, 'restore']);
    Route::delete('/forceDelete/client/{id}', [clientController::class, 'forceDelete']);

    // PROJECT
    Route::get('/showAll/project', [ProjectController::class, 'showAll']);
    Route::post('/create/project', [ProjectController::class, 'create']);
    Route::get('/show/project/{id}', [ProjectController::class, 'show']);
    Route::get('/edit/project/{id}', [ProjectController::class, 'edit']);
    Route::post('/update/project/{id}', [ProjectController::class, 'update']);
    Route::delete('/softDelete/project/{id}', [ProjectController::class, 'destroy']);
    Route::get('/showDeleted/project', [ProjectController::class, 'showDeleted']);
    Route::get('/restore/project/{id}', [ProjectController::class, 'restore']);
    Route::delete('/forceDelete/project/{id}', [ProjectController::class, 'forceDelete']);

    //ROLE&PERMISSION
    Route::get('/showAll/roles', [RolesAndPermissionsController::class, 'showAllRoles']);
    Route::post('/create/role', [RolesAndPermissionsController::class, 'createRole']);
    Route::get('/show/role/{id}', [RolesAndPermissionsController::class, 'showRole']);
    Route::get('/edit/role/{id}', [RolesAndPermissionsController::class, 'editRole']);
    Route::put('/update/role/{id}', [RolesAndPermissionsController::class, 'updateRole']);
    Route::delete('/softDelete/role/{id}', [RolesAndPermissionsController::class, 'deleteRole']);
    Route::get('/showDeleted/role/{id}', [RolesAndPermissionsController::class, 'showDeletedRole']);
    Route::get('/restore/role/{id}', [RolesAndPermissionsController::class, 'restoreRole']);
    Route::delete('/forceDelete/role/{id}', [RolesAndPermissionsController::class, 'forceDeleteRole']);
    Route::get('/showAll/permissions', [RolesAndPermissionsController::class, 'showAllPermissions']);
    Route::post('/create/permission', [RolesAndPermissionsController::class, 'createPermission']);
    Route::get('/show/permission/{id}', [RolesAndPermissionsController::class, 'showPermission']);
    Route::get('/edit/permission/{id}', [RolesAndPermissionsController::class, 'editPermission']);
    Route::put('/update/permission/{id}', [RolesAndPermissionsController::class, 'updatePermission']);
    Route::delete('/softDelete/permission/{id}', [RolesAndPermissionsController::class, 'deletePermission']);
    Route::get('/showDeleted/permission/{id}', [RolesAndPermissionsController::class, 'showDeletedPermission']);
    Route::get('/restore/permission/{id}', [RolesAndPermissionsController::class, 'restorePermission']);
    Route::delete('/forceDelete/permission/{id}', [RolesAndPermissionsController::class, 'forceDeletePermission']);

    Route::post('/assign/role/{role}/to/permissions', [RolesAndPermissionsController::class, 'assignRoleToPermissions']);
    Route::delete('/revoke/role/{roleId}/from/permissions', [RolesAndPermissionsController::class, 'revokeRoleFromPermissions']);
    Route::delete('/revoke/role/{roleId}/from/permission/{permissionId}', [RolesAndPermissionsController::class, 'revokeRoleFromPermission']);


    Route::get('/showAll/rolesWithPermissions', [RolesAndPermissionsController::class, 'showAllRolesWithPermissions']);

    Route::post('/assign/role/{roleId}/to/user/{userId}', [RolesAndPermissionsController::class, 'assignRoleToUser']);
    Route::delete('/revoke/role/{roleId}/from/user/{userId}', [RolesAndPermissionsController::class, 'revokeRoleFromUser']);

    Route::post('/assign/permissions/to/user/{user}', [RolesAndPermissionsController::class, 'assignPermissionsToUser']);
    Route::delete('/revoke/permission/{permissionId}/from/user/{userId}', [RolesAndPermissionsController::class, 'revokePermissionFromUser']);
    Route::delete('/revoke/user/{userId}/from/permissions', [RolesAndPermissionsController::class, 'revokeUserFromPermissions']);

    Route::get('/showAll/usersWithRoles', [RolesAndPermissionsController::class, 'showAllRolesWithUsers']);
    Route::get('/showAll/usersWithPermissions', [RolesAndPermissionsController::class, 'showAllPermissionsWithUsers']);

    // STATISTICS
    Route::get('showAll/statistics', [StatisticsController::class, 'showStatistics']);


});

Route::group([
    'middleware' => 'auth',
    'prefix' => 'auth'
], function () {
    // COMMENT
    Route::get('/showAll/comment', [CommentUserController::class, 'showAll']);
    Route::post('/create/comment', [CommentUserController::class, 'create']);
    Route::get('/show/comment/{id}', [CommentUserController::class, 'show']);
    Route::get('/edit/comment/{id}', [CommentUserController::class, 'edit']);
    Route::put('/update/comment/{id}', [CommentUserController::class, 'update']);
    Route::delete('/forceDelete/comment/{id}', [CommentUserController::class, 'forceDelete']);

    // CONTACT
    Route::post('/create/contact', [ContactUserController::class, 'create']);
    Route::get('/show/contact/{id}', [ContactUserController::class, 'show']);
    Route::get('/edit/contact/{id}', [ContactUserController::class, 'edit']);
    Route::put('/update/contact/{id}', [ContactUserController::class, 'update']);
    Route::delete('/forceDelete/contact/{id}', [ContactUserController::class, 'forceDelete']);

    // JOB
    Route::post('/create/job', [JobUserController::class, 'create']);
    Route::get('/show/job/{id}', [JobUserController::class, 'show']);
    Route::get('/edit/job/{id}', [JobUserController::class, 'edit']);
    Route::put  ('/update/job/{id}', [JobUserController::class, 'update']);
    Route::delete('/forceDelete/job/{id}', [JobUserController::class, 'forceDelete']);

    // ORDER
    Route::post('/create/order', [OrderUserController::class, 'create']);
    Route::get('/show/order/{id}', [OrderUserController::class, 'show']);
    Route::get('/edit/order/{id}', [OrderUserController::class, 'edit']);
    Route::put('/update/order/{id}', [OrderUserController::class, 'update']);
    Route::delete('/forceDelete/order/{id}', [OrderUserController::class, 'forceDelete']);



});
