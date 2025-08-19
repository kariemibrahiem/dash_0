<?php

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




Route::prefix('users')->group(function () {
    Route::get('get-data', [\App\Http\Controllers\Api\V1\UserApiController::class, 'getData'])->name('users.index');
    Route::get('/{id}', [\App\Http\Controllers\Api\V1\UserApiController::class, 'getById'])->name('users.show');
    Route::post('/', [\App\Http\Controllers\Api\V1\UserApiController::class, 'store'])->name('users.store');
    Route::put('/{id}', [\App\Http\Controllers\Api\V1\UserApiController::class, 'update'])->name('users.update');
    Route::delete('/{id}', [\App\Http\Controllers\Api\V1\UserApiController::class, 'destroy'])->name('users.destroy');
});
