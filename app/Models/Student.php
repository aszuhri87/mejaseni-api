<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Uuid;
use Storage;

class Student extends Authenticatable
{
    use HasFactory, Notifiable, Uuid, SoftDeletes;

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
        'actived',
        'verified',
        'provider',
        'token_verification',
        'token_expired_at',
        'change_email'
    ];

    protected $dates = ['deleted_at'];

    public function getImageUrl()
    {
        $path = Storage::disk('s3')->url('/');
        return $path . $this->image;
    }
}
