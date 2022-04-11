<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostsController;
use App\Http\Controllers\Api\RolesNdPermissions\PermissionController;
use App\Http\Controllers\Api\RolesNdPermissions\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Controller\Api;

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


// Login & Register Routes

Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);



Route::group(['prefix' => 'v1', 'middleware' => ['auth:api']], function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('image', [PostsController::class, 'store']);
    Route::get('/image', [PostsController::class, 'index']);

    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/create', [RoleController::class, 'store']);
        Route::post('/permissionToRole', [RoleController::class, 'givePermissionToRole']);
    });

    Route::group(['prefix' => 'permissions'], function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::post('/create', [PermissionController::class, 'store']);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [AuthController::class, 'index']);
        Route::delete('/{id}', [AuthController::class, 'destroy']);
        Route::post('/assignRole', [AuthController::class, 'assignRole']);
    });
});
