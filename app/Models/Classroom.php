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
        'student_classroom',
        'classroom_tools',
        'session',
        'coach_classroom',
        'classroom_feedback',
        'cart',
    ];

    protected $dates = ['deleted_at'];

    public function student_classroom()
    {
        return $this->hasMany(StudentClassroom::class, 'classroom_id', 'id');
    }

    public function classroom_tools()
    {
        return $this->hasMany(ClassroomTools::class, 'classroom_id', 'id');
    }

    public function session()
    {
        return $this->hasMany(Session::class, 'classroom_id', 'id');
    }

    public function coach_classroom()
    {
        return $this->hasMany(CoachClassroom::class, 'classroom_id', 'id');
    }

    public function classroom_feedback()
    {
        return $this->hasMany(ClassroomFeedback::class, 'classroom_id', 'id');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'classroom_id', 'id');
    }
}
