<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class GuestStar extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'coach_id',
        'expertise_id',
        'name',
        'image',
        'description'
    ];

    protected $dates = ['deleted_at'];
}
