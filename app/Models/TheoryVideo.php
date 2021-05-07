<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use App\Traits\SoftDeleteCascade;
use App\Traits\RestoreSoftDeletes;

class TheoryVideo extends Model
{
    use HasFactory, Uuid, SoftDeletes;

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

    public $cascadeDeletes = [
        'video_metadatas',
    ];

    protected $dates = ['deleted_at'];
    public function video_metadatas()
    {
        return $this->hasMany(VideoMetadata::class, 'theory_video_id', 'id');
    }
}
