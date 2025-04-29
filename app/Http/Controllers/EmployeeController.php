<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user')->get();
        return response()->json($employees);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'employee_code' => 'required|unique:employees',
            'position' => 'required',
            'hire_date' => 'required|date',      
        ]);

        $employee = Employee::create($validated);

        return response()->json($employee, 201);
    }

    public function show($id)
    {
        $employee = Employee::with('user')->findOrFail($id);

        return response()->json($employee);
    }

    public function update(Request $request,$id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'employee_code' => 'required|unique:employees,employee_code,'. $id,
            'position' => 'required',
            'hire_date' => 'requored|date',
        ]);

        $employee->update($validated);

        return response()->json($employee);

    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return response()->json(null, 204);
    }
}
