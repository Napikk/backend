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
        
    });

    //kabeh iki
    Route::middleware('role:HR,admin,karyawan')->group(function () {
        Route::get('/employees', [EmployeeController::class, 'index']);
    });

    //hr
    Route::middleware('role:HR')->group(function () {
        Route::post('/salaries/{id}/approve', [SalaryController::class, 'approve']);
        // Route::get('/salary-approval-logs', [SalaryApprovalLogController::class, 'index']);

        Route::post('/attendances/{id}/gg', [AttendanceController::class, 'approve']);
        // Route::get('/attendance-approval-logs', [AttendanceApprovalLogController::class, 'index']);

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
        Route::post('/salary-claims', [SalaryClaimController::class, 'store']);
        Route::get('/salary-claims', [SalaryClaimController::class, 'index']);

        Route::post('/salary/my', [SalaryController::class, 'store']);

        Route::post('/izin', [AttendanceController::class, 'store']);
        // Route::get('/izin', [AttendanceController::class, 'index']);

        Route::post('/absen/masuk', [AbsensiController::class, 'absenMasuk']);
        Route::post('/absen/pulang', [AbsensiController::class, 'absenPulang']);
        Route::get('/absensi/histori', [AbsensiController::class, 'histori']);
    });
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//admin
// Route::middleware('role:admin')->group(function () {
//     // Route::get('/users', [UserController::class, 'index']);
//     // Route::get('/position', [PositionController::class, 'index']);
//     // Route::get('/attendance-approval-logs', [AttendanceApprovalLogController::class, 'index']);
//     Route::get('/salary-approval-logs', [SalaryApprovalLogController::class, 'index']);
//     Route::get('/employees', [EmployeeController::class, 'index']);
// });
// hr
// Route::middleware(['auth:sanctum', 'role:HR'])->group(function () {
//     Route::post('/salaries/{id}/approve', [SalaryController::class, 'approve']);
//     Route::get('/salary-approval-logs', [SalaryApprovalLogController::class, 'index']);

//     Route::post('/attendances/{id}/approve', [AttendanceController::class, 'approve']);
//     Route::get('/attendance-approval-logs', [AttendanceApprovalLogController::class, 'index']);

//     Route::get('/users/hr', [UserController::class, 'index']);
//     Route::get('/users', [UserController::class, 'index']);
//     Route::delete('/users/{id}', [UserController::class, 'destroy']);

// Route::get('/position', [PositionController::class, 'index']);
// Route::post('/position', [PositionController::class, 'store']);
// Route::put('/position/{id}', [PositionController::class, 'update']);
// Route::delete('/position/{id}', [PositionController::class, 'destroy']);
// Route::apiResource('positions', PositionController::class)->except('create');

// Route::get('/employee', [EmployeeController::class, 'index']);
// Route::post('/employee', [EmployeeController::class, 'store']);
// Route::put('/employee/{id}', [EmployeeController::class, 'update']);
// Route::delete('/employee/{id}', [EmployeeController::class, 'destroy']);

// });

// admin
// Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {



// });

// karyawan
// Route::middleware(['auth:sanctum', 'role:karyawan'])->group(function () {

// });

