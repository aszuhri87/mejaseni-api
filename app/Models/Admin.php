<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\Uuid;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, Uuid, SoftDeletes, HasRoles;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password'
    ];

    protected $dates = ['deleted_at'];
}
