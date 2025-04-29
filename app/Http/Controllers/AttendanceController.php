<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceApprovalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('employee')->get();
        return response()->json($attendances);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:present,sick,alpha',
            'note' => 'nullable|string'
        ]);

        $attendance = Attendance::create($validated + ['approved' =>false]);

        return response()->json($attendance, 201);
    }

    public function approve($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->update(['approved' => true]);

        AttendanceApprovalLog::create([
            'attendance_id' => $attendance->id,
            'approved_by' =>Auth::id(),
            'approval_date'=> now(),
        ]);

        return response()->json(['message'=>'Kehadiran berhasil disetujui']);
    }
}
