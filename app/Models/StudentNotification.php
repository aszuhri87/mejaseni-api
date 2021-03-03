<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class StudentNotification extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'student_id',
        'transaction_id',
        'student_schedule_id',
        'is_read',
        'text',
        'type',
        'datetime'
    ];

    protected $dates = ['deleted_at'];
}
