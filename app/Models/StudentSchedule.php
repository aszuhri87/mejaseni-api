<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use App\Traits\SoftDeleteCascade;
use App\Traits\RestoreSoftDeletes;

class StudentSchedule extends Model
{
    use HasFactory, Uuid, SoftDeletes, SoftDeleteCascade, RestoreSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'student_classroom_id',
        'session_id',
        'coach_schedule_id',
        'check_in'
    ];

    public $cascadeDeletes = [
        'session_feedback',
        'student_feedback',
        'student_notifications',
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

    public function student_notifications()
    {
        return $this->hasMany(StudentNotification::class, 'student_schedule_id', 'id');
    }
}
