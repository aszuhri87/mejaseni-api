<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use App\Traits\SoftDeleteCascade;
use App\Traits\RestoreSoftDeletes;

class Assignment extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    public $cascadeDeletes = [
        'collections',
    ];

    protected $fillable = [
        'session_id',
        'name',
        'file_url',
        'description',
        'upload_date',
        'due_time'
    ];

    protected $dates = ['deleted_at'];

    public function collections()
    {
        return $this->hasMany(Collection::class, 'assignment_id', 'id');
    }
}
