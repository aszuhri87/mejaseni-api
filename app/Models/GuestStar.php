<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use App\Traits\SoftDeleteCascade;
use App\Traits\RestoreSoftDeletes;

class GuestStar extends Model
{
    use HasFactory, Uuid, SoftDeletes, SoftDeleteCascade, RestoreSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'coach_id',
        'expertise_id',
        'name',
        'image',
        'description'
    ];

    public $cascadeDeletes = [
        'guest_star_master_lessons',
    ];

    protected $dates = ['deleted_at'];

    public function guest_star_master_lessons()
    {
        return $this->hasMany(GuestStarMasterLesson::class, 'guest_star_id', 'id');
    }
}
