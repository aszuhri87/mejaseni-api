<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class SessionVideo extends Model
{
    use HasFactory, Uuid, SoftDeletes, CascadeSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'sub_classroom_category_id',
        'expertise_id',
        'coach_id',
        'description',
        'price',
        'name',
        'datetime'
    ];

    protected $cascadeDeletes = [
        'theory_videos',
        'session_video_feedback',
        'carts',
        'theory_video_files',
        'incomes',
    ];

    protected $dates = ['deleted_at'];

    public function theory_videos()
    {
        return $this->hasMany(TheoryVideo::class, 'session_video_id', 'id');
    }
    public function session_video_feedback()
    {
        return $this->hasMany(SessionVideoFeedback::class, 'session_video_id', 'id');
    }
    public function theory_video_files()
    {
        return $this->hasMany(TheoryVideoFile::class, 'session_video_id', 'id');
    }
    public function carts()
    {
        return $this->hasMany(Cart::class, 'session_video_id', 'id');
    }
    public function incomes()
    {
        return $this->hasMany(Income::class, 'session_video_id', 'id');
    }
}
