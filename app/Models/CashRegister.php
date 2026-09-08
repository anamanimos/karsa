<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashRegister extends Model
{
    use BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'user_id',
        'opened_at',
        'closed_at',
        'initial_cash',
        'total_cash_sales',
        'total_non_cash_sales',
        'total_cash_in',
        'total_cash_out',
        'expected_cash',
        'actual_cash',
        'difference',
        'status', // open, closed
        'notes',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'initial_cash' => 'double',
        'total_cash_sales' => 'double',
        'total_non_cash_sales' => 'double',
        'total_cash_in' => 'double',
        'total_cash_out' => 'double',
        'expected_cash' => 'double',
        'actual_cash' => 'double',
        'difference' => 'double',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public static function currentOpenRegister(?int $userId = null): ?self
    {
        $userId = $userId ?? auth()->id();
        return self::where('user_id', $userId)
            ->where('status', 'open')
            ->latest('opened_at')
            ->first();
    }
}
