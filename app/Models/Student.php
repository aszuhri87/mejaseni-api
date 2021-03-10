<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Dyrynda\Database\Support\CascadeSoftDeletes;

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

    protected $cascadeDeletes = [
        'transaction',
        'student_classroom',
        'classroom_feedback',
        'cart',
        'session_feedback',
        'student_notification',
        'collection',
    ];

    protected $dates = ['deleted_at'];

    public function getImageUrl()
    {
        $path = Storage::disk('s3')->url('/');
        return $path . $this->image;
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'student_id', 'id');
    }

    public function student_classroom()
    {
        return $this->hasMany(StudentClassroom::class, 'student_id', 'id');
    }

    public function classroom_feedback()
    {
        return $this->hasMany(ClassroomFeedback::class, 'student_id', 'id');
    }

    public function collection()
    {
        return $this->hasMany(Collection::class, 'student_id', 'id');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'student_id', 'id');
    }

    public function session_feedback()
    {
        return $this->hasMany(SessionFeedback::class, 'student_id', 'id');
    }

    public function student_notification()
    {
        return $this->hasMany(StudentNotification::class, 'student_id', 'id');
    }
}
