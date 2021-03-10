<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class Classroom extends Model
{
    use HasFactory, Uuid, SoftDeletes, CascadeSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'classroom_category_id',
        'sub_classroom_category_id',
        'package_type',
        'sub_package_type',
        'type',
        'name',
        'description',
        'image',
        'price',
        'session_total',
        'session_duration'
    ];

    protected $cascadeDeletes = [
        'student_classrooms',
        'classroom_tools',
        'sessions',
        'coach_classrooms',
        'classroom_feedbacks',
        'carts',
    ];

    protected $dates = ['deleted_at'];

    public function student_classrooms()
    {
        return $this->hasMany(StudentClassroom::class, 'classroom_id', 'id');
    }

    public function classroom_tools()
    {
        return $this->hasMany(ClassroomTools::class, 'classroom_id', 'id');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class, 'classroom_id', 'id');
    }

    public function coach_classrooms()
    {
        return $this->hasMany(CoachClassroom::class, 'classroom_id', 'id');
    }

    public function classroom_feedbacks()
    {
        return $this->hasMany(ClassroomFeedback::class, 'classroom_id', 'id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'classroom_id', 'id');
    }
}
