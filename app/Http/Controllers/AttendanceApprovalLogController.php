<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceApprovalLog;
use Illuminate\Http\Request;

class AttendanceApprovalLogController extends Controller
{
    public function index()
    {
        $logs = AttendanceApprovalLog::with(['attendance', 'approver'])->get();
        return response()->json($logs);
    }
}
