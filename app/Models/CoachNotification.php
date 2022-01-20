<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class CoachNotification extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    // Type Description
    // 1 Transaction Success
    // 2 Schedule Confirmed
    // 3 Reschedule
    // 4 Income Transaction Success

    protected $fillable = [
        'coach_id',
        'transaction_id',
        'coach_schedule_id',
        'income_transaction_id',
        'is_read',
        'text',
        'type',
        'datetime'
    ];

    protected $dates = ['deleted_at'];
}
