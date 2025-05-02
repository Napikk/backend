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
    
    public function attendance(){
        return $this->belongsTo(Attendance::class, "attendance_id");
    }

    public function approver(){
        return $this->belongsTo(user::class, "approved_by");
    }
}
