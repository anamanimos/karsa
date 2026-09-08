<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'user_id',
        'code',
        'name',
        'phone',
        'email',
        'position',
        'employment_status',
        'join_date',
        'base_salary',
        'fixed_allowance',
        'daily_allowance',
        'overtime_rate_per_hour',
        'commission_rate',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'base_salary' => 'double',
        'fixed_allowance' => 'double',
        'daily_allowance' => 'double',
        'overtime_rate_per_hour' => 'double',
        'commission_rate' => 'double',
        'is_active' => 'boolean',
        'join_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(EmployeeAttendance::class);
    }

    public function bonuses(): HasMany
    {
        return $this->hasMany(EmployeeBonus::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class)->latest('period');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'cashier_employee_id');
    }
}
