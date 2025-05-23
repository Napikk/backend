<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'base_salary',
        'bonus',
        'tunjangan',
        'position_name',
        'total',
        'approved',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function salaryapprovallog(){
        return $this->hasMany(SalaryApprovalLog::class);
    }

    public function salaryclaim(){
        return $this->hasMany(SalaryClaim::class);
    }

}
