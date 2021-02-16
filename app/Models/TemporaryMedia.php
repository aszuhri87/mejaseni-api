<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class TemporaryMedia extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'path',
    ];
}
