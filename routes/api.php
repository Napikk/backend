<?php

use App\Http\Controllers\AttendanceApprovalLogController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
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

Route::apiResource('employee', EmployeeController::class);
Route::apiResource('salaries', SalaryController::class);
Route::apiResource('salary_claims', SalaryClaimController::class);
Route::get('/salary_approval_logs', [SalaryApprovalLogController::class, 'index']);
Route::get('/attendance_approval_logs', [AttendanceApprovalLogController::class, 'index']);

// hr
Route::middleware(['auth:sanctum', 'role:hr'])->group(function () {
    Route::post('/salaries/{id}/approve', [SalaryController::class, 'approve']);
    Route::get('/salary-approval-logs', [SalaryApprovalLogController::class, 'index']);
});

// admin
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/attendances/{id}/approve', [AttendanceController::class, 'approve']);
    Route::get('/attendance-approval-logs', [AttendanceApprovalLogController::class, 'index']);
    Route::get('/users', [UserController::class, 'index']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});

// karyawan
Route::middleware(['auth:sanctum', 'role:karyawan'])->group(function () {
    Route::post('/salary-claims', [SalaryClaimController::class, 'store']);
    Route::get('/salary-claims', [SalaryClaimController::class, 'index']);
    
    Route::post('/attendances', [AttendanceController::class, 'store']);
    Route::get('/attendances/my', [AttendanceController::class, 'myAttendances']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
