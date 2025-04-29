<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\SalaryApprovalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    public function index()
    {
        $salaries = Salary::with('employee')->get();
        return response()->json($salaries);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'base_salary' => 'required|numeric',
            'bonus' => 'nullable|numeric',
        ]);

        $salary = Salary::create($validated + ['approved' => false]);

        return response()->json($salary, 201);
    }

    public function show($id)
    {
        $salary = Salary::with('employee')->findOrFail($id);
        return response()->json($salary);
    }

    public function update(Request $request,$id)
    {
        $salary = Salary::findOrFail($id);

        $validated = $request->validate([
            'base_salary' => 'required|numeric',
            'bonus'=>'nullable|numeric'
        ]);

        $salary->update($validated);

        return response()->json($salary);
    }

    public function destroy($id)
    {
        $salary = Salary::findOrFail($id);
        $salary->delete();

        return response()->json(null, 204);
    }

    public function approve($id)
    {
        $salary = Salary::findOrFail($id);
        $salary->update(['approved' => true]);

        SalaryApprovalLog::create([
            'salary_id' =>$salary->id,
            'approved_by'=>Auth::id(),
            'approval_date'=>now(),
        ]);

        return response()->json(['message' => 'Gaji berhasil disetujui']);
    }
}
