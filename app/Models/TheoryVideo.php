<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class TheoryVideo extends Model
{
    use HasFactory, Uuid, SoftDeletes, CascadeSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'session_video_id',
        'name',
        'is_youtube',
        'youtube_url',
        'file_name',
        'file_name_original',
        'is_converter_complete',
        'duration',
        'number',
        'is_public',
        'video_url',
    ];

    protected $cascadeDeletes = [
        'video_metadata',
    ];

    protected $dates = ['deleted_at'];
    public function video_metadata()
    {
        return $this->hasMany(VideoMetadata::class, 'theory_video_id', 'id');
    }
}
