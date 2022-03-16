<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'master_lesson_id',
        'session_video_id',
        'classroom_id',
        'student_id',
        'theory_id',
        'event_id',
        'lat',
        'lng',
        'address',
    ];

    protected $dates = ['deleted_at'];
}
