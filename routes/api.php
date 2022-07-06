<?php

use App\Http\Controllers\AdministratorController;
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

Route::get("/admin", [AdministratorController::class, 'index'])->name('get-admin');
Route::group(['prefix' => 'user'], function () {
    Route::post('/', [AdministratorController::class, 'submitUser']);
    Route::get('/show-request', [AdministratorController::class, 'showRequests']);
    Route::post('update/{User:id}', [AdministratorController::class, 'submitUserUpdate']);
    Route::get('/approve-request/{userEdit:id}', [AdministratorController::class, 'approveRequest']);
  });
