<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Uuid;

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
        'verified'
    ];

    protected $dates = ['deleted_at'];
}
