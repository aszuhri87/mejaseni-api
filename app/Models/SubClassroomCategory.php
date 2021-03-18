<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use App\Traits\SoftDeleteCascade;
use App\Traits\RestoreSoftDeletes;

class SubClassroomCategory extends Model
{
    use HasFactory, Uuid, SoftDeletes, SoftDeleteCascade, RestoreSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'classroom_category_id',
        'profile_coach_video_id',
        'name',
        'image'
    ];

    public $cascadeDeletes = [
        'master_lessons',
        'session_videos',
        'classrooms',
    ];

    protected $dates = ['deleted_at'];

    public function master_lessons()
    {
        return $this->hasMany(MasterLesson::class, 'sub_classroom_category_id', 'id');
    }

    public function session_videos()
    {
        return $this->hasMany(SessionVideo::class, 'sub_classroom_category_id', 'id');
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'sub_classroom_category_id', 'id');
    }
}
