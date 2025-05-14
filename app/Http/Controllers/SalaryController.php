<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use App\Models\Salary;
use App\Models\SalaryApprovalLog;
use Carbon\Carbon;
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
        /*$user = Auth::user();
        $id = $user->id;*/
    
        $validated = $request->validate([
            "employee_id" => "required|exists:employees,id",
            'base_salary' => 'required|numeric',
            'bonus' => 'nullable|numeric',
            // 'tunjangan' => 'nullable|numeric'
        ]);
    
        $lastSalary = Salary::where('employee_id', $request->employee_id)
            ->orderBy('created_at', 'desc')
            ->first();
    
        if ($lastSalary) {
            $oneMonthAgo = Carbon::now()->subMonth();
            if ($lastSalary->created_at->greaterThan($oneMonthAgo)) {
                return response()->json([
                    'message' => 'Kamu hanya bisa meminta gaji sekali dalam sebulan. '
                        . 'Entri gaji terakhir: '
                        . $lastSalary->created_at->toDateString()
                ], 422);
            }
        }
    
        $id = Employee::where("id", $request->employee_id)->value("position_id");
        $position = Position::where("id",$id)->value("name");
        $tunjangan = Position::where("id",$id)->value("tunjangan");

    
        $bonus = $validated['bonus'] ?? 0;
        $total = $validated['base_salary'] + $tunjangan + $bonus;
    
        $salary = Salary::create([
            'employee_id'    => $request->employee_id,
            'base_salary'    => $validated['base_salary'],
            'bonus'          => $bonus,
            'tunjangan'      => $tunjangan,
            'position_name'  => $position,
            'total'          => $total,
        ]);
    
        return response()->json($salary, 201);
    }
        public function show($id)
    {
        $salary = Salary::with('employee')->findOrFail($id);
        return response()->json($salary);
    }

   public function update(Request $request, $id)
    {
        $salary = Salary::findOrFail($id);

        $validated = $request->validate([
            'base_salary' => 'required|numeric',
            'bonus' => 'nullable|numeric'
        ]);

        $bonus = $validated['bonus'] ?? 0;
        $tunjangan = $salary->tunjangan; // pakai nilai tunjangan dari data yang sudah ada
        $total = $validated['base_salary'] + $bonus + $tunjangan;

        $salary->update([
            'base_salary' => $validated['base_salary'],
            'bonus' => $bonus,
            'total' => $total
        ]);

        return response()->json($salary);
    }

    public function destroy($id)
    {
        $salary = Salary::findOrFail($id);
        $salary->delete();

        return response()->json(null, 204);
    }

    // public function approve($id)
    // {
    //     $salary = Salary::findOrFail($id);
    //     $salary->update(['approved' => true]);

    //     SalaryApprovalLog::create([
    //         'salary_id' => $salary->id,
    //         'approved_by' => Auth::id(),
    //         'approval_date' => now(),
    //     ]);

    //     return response()->json(['message' => 'Gaji berhasil disetujui']);
    // }
}
