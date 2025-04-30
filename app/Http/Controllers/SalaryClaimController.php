<?php

namespace App\Http\Controllers;

use App\Models\SalaryClaim;
use Illuminate\Http\Request;

class SalaryClaimController extends Controller
{
    public function index()
    {
        $claims = SalaryClaim::with(['employee', 'salary'])->get();
        return response()->json($claims);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'salary_id' => 'required|exists:salaries,id',
            'employee_id' => 'required|exists:employees,id',
            'claim_date' => 'required|date',
        ]);

        $claim = SalaryClaim::create($validated + ['status' => 'pending']);

        return response()->json($claim, 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $claim = SalaryClaim::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,completed',
        ]);

        $claim->update($validated);

        return response()->json($claim);
    }    
    
}
