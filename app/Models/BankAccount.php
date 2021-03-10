<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class BankAccount extends Model
{
    use HasFactory, Uuid, SoftDeletes, CascadeSoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = [
        'coach_id',
        'bank',
        'bank_number',
        'name_account'
    ];

    protected $cascadeDeletes = [
        'income_transactions',
    ];

    protected $dates = ['deleted_at'];

    public function income_transactions()
    {
        return $this->hasMany(IncomeTransaction::class, 'bank_id', 'id');
    }
}
