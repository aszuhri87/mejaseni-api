<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use App\Traits\SoftDeleteCascade;
use App\Traits\RestoreSoftDeletes;

class CoachClassroom extends Model
{
    use HasFactory, Uuid, SoftDeletes, SoftDeleteCascade, RestoreSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'classroom_id',
        'coach_id'
    ];

    public $cascadeDeletes = ['coach_schedules'];

    protected $dates = ['deleted_at'];

    public function coach_schedules()
    {
        return $this->hasMany(CoachSchedule::class, 'coach_classroom_id', 'id');
    }
}
