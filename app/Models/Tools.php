<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class Tools extends Model
{
    use HasFactory, Uuid, SoftDeletes, CascadeSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'text'
    ];

    protected $cascadeDeletes = [
        'classroom_tool',
    ];

    protected $dates = ['deleted_at'];

    public function classroom_tool()
    {
        return $this->hasMany(ClassroomTools::class, 'tool_id', 'id');
    }
}
