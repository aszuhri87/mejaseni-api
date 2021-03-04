<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Cart extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'master_lesson_id',
        'session_video_id',
        'classroom_id',
        'student_id',
        'theory_id',
        'event_id'
    ];

    protected $dates = ['deleted_at'];
}
