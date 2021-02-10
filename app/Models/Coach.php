<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Uuid;

class Coach extends Authenticatable
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
        'description',
        'image',
        'expertise'
    ];

    protected $dates = ['deleted_at'];
}
