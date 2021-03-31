<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class PrivacyPolicyItem extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'title',
        'description',
        'privacy_policy_id',
        'quill_description',
        'json_description'
    ];

    protected $dates = ['deleted_at'];
}
