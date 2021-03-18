<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\Uuid;
use App\Traits\SoftDeleteCascade;
use App\Traits\RestoreSoftDeletes;

class Coach extends Authenticatable
{
    use HasFactory, Notifiable, Uuid, SoftDeletes, HasRoles, SoftDeleteCascade, RestoreSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone',
        'description',
        'image',
        'expertise_id',
        'suspend',
    ];

    public $cascadeDeletes = [
        'coach_classrooms',
        'student_feedbacks',
        'income_transactions',
        'guest_star',
        'bank_account',
        'coach_notifications',
        'coach_sosmeds',
        'profile_coach_video',
        'session_videos',
        'incomes',
    ];

    protected $dates = ['deleted_at'];

    public function restore()
    {
        return $this->restore_soft_deletes($this);
    }

    /**
     * Get all of the comments for the Coach
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coach_classrooms()
    {
        return $this->hasMany(CoachClassroom::class, 'coach_id', 'id');
    }
    public function student_feedbacks()
    {
        return $this->hasMany(StudentFeedback::class, 'coach_id', 'id');
    }
    public function income_transactions()
    {
        return $this->hasMany(IncomeTransaction::class, 'coach_id', 'id');
    }
    public function bank_account()
    {
        return $this->hasOne(BankAccount::class, 'coach_id', 'id');
    }
    public function guest_star()
    {
        return $this->hasOne(GuestStar::class, 'coach_id', 'id');
    }
    public function coach_notifications()
    {
        return $this->hasMany(CoachNotification::class, 'coach_id', 'id');
    }
    public function coach_sosmeds()
    {
        return $this->hasMany(CoachSosmed::class, 'coach_id', 'id');
    }
    public function profile_coach_video()
    {
        return $this->hasOne(ProfileCoachVideo::class, 'coach_id', 'id');
    }
    public function session_videos()
    {
        return $this->hasMany(SessionVideo::class, 'coach_id', 'id');
    }
    public function incomes()
    {
        return $this->hasMany(Income::class, 'coach_id', 'id');
    }
}
