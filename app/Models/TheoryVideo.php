<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class TheoryVideo extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'session_video_id',
        'name',
        'is_youtube',
        'url',
        'is_converter_complete',
        'duration'
    ];

    protected $dates = ['deleted_at'];
}
