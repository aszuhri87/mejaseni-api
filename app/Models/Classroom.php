<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Classroom extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'classroom_category_id',
        'sub_classroom_category_id',
        'package_id',
        'platform_id',
        'type',
        'name',
        'description',
        'image',
        'price',
        'session_total',
        'session_duration'
    ];

    protected $dates = ['deleted_at'];
}
