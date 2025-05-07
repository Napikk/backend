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
            'full_name'=>'required|string',
            'position_id' => 'required|exists:positions,id',
            'email'=>'required|string',
            'phone_number'=>'required|string',
            'address'=>'required|string',
            'image'=>'nullable|image|mimes:jpeg,png,jpg|max:2048',    
        ]);

        $employee = Employee::create($validated);

        return response()->json($employee, 201);
    }

    public function show($id)
    {
        $employee = Employee::with('user')->findOrFail($id);
        $employee = Employee::with('position')->findOrFail($id);

        return response()->json($employee);
    }

    public function update(Request $request,$id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
             'full_name'=>'required|string',
            'position_id' => 'required|exists:positions,id',
            'email'=>'required|string',
            'phone_number'=>'required|string',
            'address'=>'required|string',
            'image'=>'nullable|image|mimes:jpeg,png,jpg|max:2048',
           
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
