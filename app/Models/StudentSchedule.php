<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class StudentSchedule extends Model
{
    use HasFactory, Uuid, SoftDeletes, CascadeSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'student_classroom_id',
        'session_id',
        'coach_schedule_id',
        'check_in'
    ];

    protected $cascadeDeletes = [
        'session_schedule',
        'student_feedback',
        'student_notification',
    ];

    protected $dates = ['deleted_at'];

    public function session_feedback()
    {
        return $this->hasMany(SessionFeedback::class, 'student_schedule_id', 'id');
    }

    public function student_feedback()
    {
        return $this->hasMany(StudentFeedback::class, 'student_schedule_id', 'id');
    }
    
    public function student_notification()
    {
        return $this->hasMany(StudentNotification::class, 'student_schedule_id', 'id');
    }
}
