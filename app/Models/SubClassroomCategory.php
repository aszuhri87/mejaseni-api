<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class SubClassroomCategory extends Model
{
    use HasFactory, Uuid, SoftDeletes, CascadeSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'classroom_category_id',
        'profile_coach_video_id',
        'name',
        'image'
    ];

    protected $cascadeDeletes = [
        'master_lesson',
        'session_video',
        'classroom',
    ];

    protected $dates = ['deleted_at'];

    public function master_lesson()
    {
        return $this->hasMany(MasterLesson::class, 'sub_classroom_category_id', 'id');
    }

    public function session_video()
    {
        return $this->hasMany(SessionVideo::class, 'sub_classroom_category_id', 'id');
    }

    public function classroom()
    {
        return $this->hasMany(Classroom::class, 'sub_classroom_category_id', 'id');
    }
}
