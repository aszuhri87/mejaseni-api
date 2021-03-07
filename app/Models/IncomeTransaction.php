<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class IncomeTransaction extends Model
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
        'coach_id',
        'status',
        'total',
        'datetime',
        'confirmed',
        'confirmed_at',
        'image',
        'bank',
        'bank_number',
        'name_account'
    ];

    protected $dates = ['deleted_at'];
}
