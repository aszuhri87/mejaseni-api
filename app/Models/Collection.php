<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use App\Traits\SoftDeleteCascade;
use App\Traits\RestoreSoftDeletes;

class Collection extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'student_id',
        'assignment_id',
        'description',
        'upload_date',
        'name',
    ];

    public $cascadeDeletes = [
        'collection_files',
        'collection_feedback',
    ];

    protected $dates = ['deleted_at'];

    public function collection_files()
    {
        return $this->hasMany(CollectionFile::class, 'collection_id', 'id');
    }

    public function collection_feedback()
    {
        return $this->hasOne(CollectionFeedback::class, 'collection_id', 'id');
    }
}
