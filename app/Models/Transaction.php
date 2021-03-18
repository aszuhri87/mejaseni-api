<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\SoftDeleteCascade;
use App\Traits\RestoreSoftDeletes;
use App\Traits\Uuid;

class Transaction extends Model
{
    use HasFactory, Uuid, SoftDeletes, SoftDeleteCascade, RestoreSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    // Status Description
    // 0, Cancel
    // 1, Waiting
    // 2, Success
    // 3, Refund

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

    public $cascadeDeletes = ['details'];

    /**
     * Get all of the comments for the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    }
}
