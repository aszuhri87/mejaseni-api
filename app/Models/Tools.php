<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use App\Traits\SoftDeleteCascade;
use App\Traits\RestoreSoftDeletes;

class Tools extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'text'
    ];

    public $cascadeDeletes = [
        'classroom_tools',
    ];

    protected $dates = ['deleted_at'];

    public function classroom_tools()
    {
        return $this->hasMany(ClassroomTools::class, 'tool_id', 'id');
    }
}
