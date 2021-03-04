<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Traits\Uuid;

class ClassroomCategory extends Model
{
    use HasFactory, Uuid, SoftDeletes, CascadeSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'name',
        'profile_coach_video_id'
    ];

    protected $cascadeDeletes = ['sub_classroom_categories'];

    protected $dates = ['deleted_at'];

    public function sub_classroom_categories()
    {
        return $this->hasMany('App\Models\SubClassroomCategory', 'classroom_category_id', 'id');
    }
}
