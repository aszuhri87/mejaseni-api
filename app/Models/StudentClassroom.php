<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use App\Traits\SoftDeleteCascade;
use App\Traits\RestoreSoftDeletes;

class StudentClassroom extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'classroom_id',
        'transaction_id',
        'student_id'
    ];

    public $cascadeDeletes = [
        'student_schedules',
    ];

    protected $dates = ['deleted_at'];

    public function student_schedules()
    {
        return $this->hasMany(StudentSchedule::class, 'student_classroom_id', 'id');
    }
}
