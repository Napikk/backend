<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class EmployeeController extends Controller
{
    public function index(Request $request)
{
    $query = Employee::with(['user', 'position']);

    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('full_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone_number', 'like', "%{$search}%")
              ->orWhereHas('user', function ($userQuery) use ($search) {
                 $userQuery->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('position', function ($positionQuery) use ($search) {
                  $positionQuery->where('name', 'like', "%{$search}%");
              });
        });
    }

    $employees = $query->get();

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

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('employee_images', 'public');
        $validated['image'] = $imagePath;
    }

    $employee = Employee::create($validated);

    return response()->json($employee, 201);
}


    public function show($id)
    {
        $employee = Employee::with('user')->findOrFail($id);
        $employee = Employee::with('position')->findOrFail($id);

        return response()->json($employee);
    }

    public function update(Request $request, $id)
{
    $employee = Employee::findOrFail($id);

    $validated = $request->validate([
        'full_name'=>'required|string',
        'position_id' => 'required|exists:positions,id',
        'email'=>'required|string',
        'phone_number'=>'required|string',
        'address'=>'required|string',
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
