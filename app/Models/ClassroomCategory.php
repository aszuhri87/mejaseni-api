<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\SoftDeleteCascade;
use App\Traits\RestoreSoftDeletes;
use App\Traits\Uuid;

class ClassroomCategory extends Model
{
    use HasFactory, Uuid, SoftDeletes, SoftDeleteCascade, RestoreSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'name',
        'description',
        'image',
        'profile_coach_video_id',
        'empty_image',
        'empty_message',
        'classroom_id'
    ];

    public $cascadeDeletes = ['sub_classroom_categories'];

    protected $dates = ['deleted_at'];

    public function sub_classroom_categories()
    {
        return $this->hasMany('App\Models\SubClassroomCategory', 'classroom_category_id', 'id');
    }

    public function restore()
    {
        return $this->restore_soft_deletes($this);
    }
}
