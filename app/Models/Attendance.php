<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'status',
        'note',
        'approved',
    ];

    public function attendanceapproval(){
        return $this->hasMany(AttendanceApprovalLog::class);
    }

    public function employee () 
    {
        return $this->belongsTo(Employee::class);
    }
}
