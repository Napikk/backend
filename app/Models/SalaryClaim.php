<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary_id',
        'employee_id',
        'claim_date',
        'status',
    ];

    public function salary()
    {
        return $this->belongsTo(Salary::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
