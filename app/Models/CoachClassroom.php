<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoachClassroom extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'classroom_id',
        'coach_id',
    ];

    public $cascadeDeletes = ['coach_schedules'];

    protected $dates = ['deleted_at'];

    public function coach_schedules()
    {
        return $this->hasMany(CoachSchedule::class, 'coach_classroom_id', 'id');
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
