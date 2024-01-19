<?php

use App\Http\Controllers\AnnualLeaveController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
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


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile-employees', [EmployeeController::class, 'show']);
    Route::put('/update-employees', [EmployeeController::class, 'update']);

    Route::get('/leave-types', [AnnualLeaveController::class, 'getLeaveType']);

    Route::get('/annual-leaves', [AnnualLeaveController::class, 'getAnnualLeave']);
    Route::get('/annual-leaves/{id}', [AnnualLeaveController::class, 'getAnnualLeaveID']);
    Route::post('/annual-leaves', [AnnualLeaveController::class, 'postAnnualLeave']);
});
