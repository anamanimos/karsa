<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeBonus extends Model
{
    use BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'employee_id',
        'period',
        'date',
        'title',
        'type',
        'amount',
        'description',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'double',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
