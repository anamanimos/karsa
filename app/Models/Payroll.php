<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    use BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'payroll_number',
        'employee_id',
        'period',
        'payment_date',
        'base_salary',
        'fixed_allowance',
        'attendance_allowance',
        'overtime_pay',
        'bonus_pay',
        'total_allowances',
        'absence_deduction',
        'loan_deduction',
        'bpjs_deduction',
        'other_deductions',
        'total_deductions',
        'net_salary',
        'status',
        'cash_transaction_id',
        'paid_from_account_id',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'base_salary' => 'double',
        'fixed_allowance' => 'double',
        'attendance_allowance' => 'double',
        'overtime_pay' => 'double',
        'bonus_pay' => 'double',
        'total_allowances' => 'double',
        'absence_deduction' => 'double',
        'loan_deduction' => 'double',
        'bpjs_deduction' => 'double',
        'other_deductions' => 'double',
        'total_deductions' => 'double',
        'net_salary' => 'double',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function cashTransaction(): BelongsTo
    {
        return $this->belongsTo(CashTransaction::class);
    }

    public function paidFromAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'paid_from_account_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
