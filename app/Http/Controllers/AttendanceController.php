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
        'date_start' => 'required|date',
        'date_end' => 'required|date|after_or_equal:date_start',
        'note' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('attendance_images', 'public');
        $validated['image'] = $imagePath;
    }

    $validated['approved'] = false;

    $attendance = Attendance::create($validated);

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
