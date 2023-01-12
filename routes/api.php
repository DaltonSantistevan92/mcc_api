<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoleHasPermissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//public routes
Route::get('loginUser',[AuthController::class, 'loginUser']);
Route::post('createUser',[AuthController::class, 'createUser']);

//protected routes
Route::get('roles',[RoleController::class, 'getAll']);
Route::post('roles',[RoleController::class, 'createRoles']);


Route::post('permissions',[PermissionController::class, 'createPermission']);
Route::get('permissions',[PermissionController::class, 'getAll']);

Route::post('role_permissions',[RoleHasPermissionController::class, 'createAssignment']);



