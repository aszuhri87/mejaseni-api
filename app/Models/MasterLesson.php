<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class MasterLesson extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'classroom_category_id',
        'sub_classroom_category_id',
        'price',
        'datetime',
        'platform_id',
        'name',
        'poster',
        'slot',
        'platform_link',
        'description',
        'buy_btn_disable',
        'hide',
    ];

    protected $dates = ['deleted_at'];
}
