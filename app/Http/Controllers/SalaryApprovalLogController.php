<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\SalaryApprovalLog;
use Illuminate\Http\Request;

class SalaryApprovalLogController extends Controller
{
    public function index()
    {
        $logs = SalaryApprovalLog::with(['salary', 'approver'])->get();
        return response()->json($logs);
    }
}
