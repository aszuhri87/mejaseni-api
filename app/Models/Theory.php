<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use App\Traits\SoftDeleteCascade;
use App\Traits\RestoreSoftDeletes;

class Theory extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'session_id',
        'name',
        'is_premium',
        'is_video',
        'url',
        'description',
        'upload_date',
        'price',
        'confirmed'
    ];

    public $cascadeDeletes = [
        'carts',
    ];

    protected $dates = ['deleted_at'];

    public function carts()
    {
        return $this->hasMany(Cart::class, 'theory_id', 'id');
    }
}
