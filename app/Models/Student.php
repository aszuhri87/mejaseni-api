<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\SoftDeleteCascade;
use App\Traits\RestoreSoftDeletes;

use App\Traits\Uuid;
use Storage;

class Student extends Authenticatable
{
    use HasFactory, Notifiable, Uuid, SoftDeletes, CascadeSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone',
        'image',
        'expertise',
        'active',
        'verified',
        'provider',
        'token_verification',
        'token_expired_at',
        'change_email'
    ];

    public $cascadeDeletes = [
        'transactions',
        'student_classrooms',
        'classroom_feedback',
        'carts',
        'session_feedback',
        'student_notifications',
        'collections',
    ];

    protected $dates = ['deleted_at'];

    public function getImageUrl()
    {
        $path = Storage::disk('s3')->url('/');
        return $path . $this->image;
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'student_id', 'id');
    }

    public function student_classrooms()
    {
        return $this->hasMany(StudentClassroom::class, 'student_id', 'id');
    }

    public function classroom_feedback()
    {
        return $this->hasMany(ClassroomFeedback::class, 'student_id', 'id');
    }

    public function collections()
    {
        return $this->hasMany(Collection::class, 'student_id', 'id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'student_id', 'id');
    }

    public function session_feedback()
    {
        return $this->hasMany(SessionFeedback::class, 'student_id', 'id');
    }

    public function student_notifications()
    {
        return $this->hasMany(StudentNotification::class, 'student_id', 'id');
    }
}
