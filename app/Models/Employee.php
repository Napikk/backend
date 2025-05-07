<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'position_id',
        'email',
        'phone_number',
        'address',
        'image',
    ];

    public function attendance(){
        return $this->hasMany(Attendance::class);
    }

    public function attendanceapproval(){
        return $this->hasMany(AttendanceApprovalLog::class);
    }

    public function position(){
        return $this->belongsTo(Position::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function salary(){
        return $this->belongsTo(Salary::class);
    }


}
