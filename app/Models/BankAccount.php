<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class BankAccount extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'coach_id',
        'bank',
        'bank_number',
        'name_account'
    ];

    protected $dates = ['deleted_at'];
}
