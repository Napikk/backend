<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceApprovalLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'approved_by',
        'approval_date',
    ];
}
