<?php

use App\Http\Controllers\v1\AdminController;
use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::post("login" , [AuthController::class, "login"]);
Route::group(["middleware" => "auth:sanctum"] , function(){
    Route::get("user-getDate" , [UserController::class, "getDate"]);
    Route::post("user-create" , [UserController::class, "createUser"]);
    Route::post("user-update" , [UserController::class, "updateUser"]);
    Route::delete("user-destroy" , [UserController::class, "destroyUser"]);
    Route::post("user-logout" , [AuthController::class, "logout"]);
    // admin routes 
    Route::get("admin-getDate" , [AdminController::class, "getDate"]);
    Route::post("admin-create" , [AdminController::class, "store"]);
    Route::post("admin-update" , [AdminController::class, "update"]);
    Route::delete("admin-destroy" , [AdminController::class, "destroy"]);
});