<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'code',
        'name',
        'type', // asset, liability, equity, revenue, expense, cogs
        'subtype',
        'initial_balance',
        'description',
        'is_active',
    ];

    protected $casts = [
        'initial_balance' => 'double',
        'is_active' => 'boolean',
    ];

    public function cashTransactions(): HasMany
    {
        return $this->hasMany(CashTransaction::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class, 'paid_from_account_id');
    }
}
