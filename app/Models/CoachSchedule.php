<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use App\Traits\SoftDeleteCascade;
use App\Traits\RestoreSoftDeletes;

class CoachSchedule extends Model
{
    use HasFactory, Uuid, SoftDeletes, SoftDeleteCascade, RestoreSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'coach_classroom_id',
        'admin_id',
        'platform_id',
        'platform_link',
        'accepted',
        'datetime'
    ];

    public $cascadeDeletes = [
        'student_schedule',
    ];

    protected $dates = ['deleted_at'];

    public function student_schedule()
    {
        return $this->hasOne(StudentSchedule::class, 'coach_schedule_id', 'id');
    }
}
