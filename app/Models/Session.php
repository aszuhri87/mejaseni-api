<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use App\Traits\SoftDeleteCascade;
use App\Traits\RestoreSoftDeletes;

class Session extends Model
{
    use HasFactory, Uuid, SoftDeletes, SoftDeleteCascade, RestoreSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'classroom_id',
        'name',
    ];

    public $cascadeDeletes = [
        'assignments',
        'theories',
    ];

    protected $dates = ['deleted_at'];

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'session_id', 'id');
    }

    public function theories()
    {
        return $this->hasMany(Theory::class, 'session_id', 'id');
    }
}
