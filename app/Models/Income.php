<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Income extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'master_lesson_id',
        'session_video_id',
        'classroom_id',
        'coach_id',
        'theory_id',
        'guest_star_id',
        'student_schedule_id',
        'transaction_id'
    ];

    protected $dates = ['deleted_at'];
}
