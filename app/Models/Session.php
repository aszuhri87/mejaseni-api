<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class Session extends Model
{
    use HasFactory, Uuid, SoftDeletes, CascadeSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'classroom_id',
        'name',
    ];

    protected $cascadeDeletes = [
        'assignment',
        'theory',
    ];

    protected $dates = ['deleted_at'];

    public function assignment()
    {
        return $this->hasMany(Assignment::class, 'session_id', 'id');
    }

    public function theory()
    {
        return $this->hasMany(Theory::class, 'session_id', 'id');
    }
}
