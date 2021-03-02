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

    protected $fillable = [
        'number',
        'coach_id',
        'status',
        'total',
        'datetime',
        'confirmed',
        'confirmed_at'
    ];

    protected $dates = ['deleted_at'];
}
