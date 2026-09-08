<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAttendance extends Model
{
    use BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'employee_id',
        'period',
        'work_days',
        'present_days',
        'sick_days',
        'permission_days',
        'absent_days',
        'overtime_hours',
        'notes',
    ];

    protected $casts = [
        'work_days' => 'integer',
        'present_days' => 'integer',
        'sick_days' => 'integer',
        'permission_days' => 'integer',
        'absent_days' => 'integer',
        'overtime_hours' => 'double',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
