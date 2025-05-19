<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AttendanceApprovalLogController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\SalaryApprovalLogController;
use App\Http\Controllers\SalaryClaimController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('employees', EmployeeController::class)->only('index');
    //hr mbek admin
    Route::middleware('role:HR,admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/positions', [PositionController::class, 'index']);
        // Route::get('/employees', [EmployeeController::class, 'index']);
        Route::get('/salaries', [SalaryController::class, 'index']);
        Route::get('/attendance-approval-logs', [AttendanceApprovalLogController::class, 'index']);
        Route::get('/salary-approval-logs', [SalaryApprovalLogController::class, 'index']);
        Route::get('/absensi/all', [AbsensiController::class, 'index']);
    });

    //kabeh iki
    Route::middleware('role:HR,admin,karyawan')->group(function () {
        Route::get('/employees', [EmployeeController::class, 'index']);
        Route::get('/absensi/histori', [AbsensiController::class, 'histori']);
    });

    //hr
    Route::middleware('role:HR')->group(function () {
        Route::post('/attendances/{id}/approve', [AttendanceController::class, 'approve']);
        Route::delete('/attendances/{id}', [AttendanceController::class, 'destroy']);

        Route::get('/users/hr', [UserController::class, 'index']);
        // Route::get('/users', [UserController::class, 'index']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        Route::apiResource('positions', PositionController::class)->except('create', 'index');

        Route::apiResource('employees', EmployeeController::class)->except('create','index');

        Route::apiResource('salaries', SalaryController::class)->except('create', 'index');
    });

    Route::middleware('role:HR,karyawan')->group(function() {
        Route::get('/izin', [AttendanceController::class, 'index']);
    });

    
    //karyawan
    Route::middleware('role:karyawan')->group(function () {

        Route::post('/izin', [AttendanceController::class, 'store']);

        Route::post('/absen/masuk', [AbsensiController::class, 'absenMasuk']);
        Route::post('/absen/pulang', [AbsensiController::class, 'absenPulang']);
    });
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});