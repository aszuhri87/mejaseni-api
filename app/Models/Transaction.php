<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Transaction extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    // Status Description
    // 0, Cancel
    // 1, Waiting
    // 2, Success

    protected $fillable = [
        'number',
        'student_id',
        'status',
        'total',
        'datetime',
        'confirmed',
        'json_transaction',
        'payment_type',
        'payment_chanel',
        'payment_url',
        'confirmed_at'
    ];

    protected $dates = ['deleted_at'];
}
