<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryApprovalLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary_id',
        'approved_by',
        'approval_date'
    ];
}
