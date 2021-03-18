<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use App\Traits\SoftDeleteCascade;
use App\Traits\RestoreSoftDeletes;

class Expertise extends Model
{
    use HasFactory, Uuid, SoftDeletes, SoftDeleteCascade, RestoreSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'name'
    ];

    public $cascadeDeletes = [
        'coaches',
        'guest_stars',
        'session_videos',
    ];

    protected $dates = ['deleted_at'];

    public function coaches()
    {
        return $this->hasMany(Coach::class, 'expertise_id', 'id');
    }

    public function guest_stars()
    {
        return $this->hasMany(GuestStar::class, 'expertise_id', 'id');
    }

    public function session_videos()
    {
        return $this->hasMany(SessionVideo::class, 'expertise_id', 'id');
    }
}
