<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class Assignment extends Model
{
    use HasFactory, Uuid, SoftDeletes, CascadeSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $cascadeDeletes = [
        'collections',
    ];

    protected $fillable = [
        'session_id',
        'name',
        'file_url',
        'description',
        'upload_date',
        'due_date'
    ];

    protected $dates = ['deleted_at'];

    public function collections()
    {
        return $this->hasMany(Collection::class, 'assignment_id', 'id');
    }
}
