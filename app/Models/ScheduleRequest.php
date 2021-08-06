<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class ScheduleRequest extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'classroom_id',
        'student_id',
        'coach_id',
        'coach_confirmed',
        'datetime',
        'session'
    ];

    protected $dates = ['deleted_at'];
}
