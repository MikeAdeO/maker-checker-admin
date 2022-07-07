<?php

use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\admin_can_check;
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


Route::prefix('admin')->name('admin.')->group(function () {
    Route::post('login', [AuthController::class, 'AdminLogin'])->name('login');


    Route::prefix('user')->name('user.')->middleware(['auth:sanctum', 'abilities:admin'])->group(function () {
        Route::post('/', [AdministratorController::class, 'submitUser'])->name('draft');
        Route::get('/show-request', [AdministratorController::class, 'showRequests'])->name('pending');
        Route::get('/approve/{userEdit:id}', [AdministratorController::class, 'approveRequest'])->name('approve')->middleware(admin_can_check::class);
        Route::post('update/{User:id}', [AdministratorController::class, 'submitUserUpdate'])->name('update');
        Route::delete('/decline/{userEdit:id}', [AdministratorController::class, 'declineRequest'])->name('decline');
        Route::get('/logout', [AuthController::class, 'adminLogOut'])->name('logout');
    });
});
