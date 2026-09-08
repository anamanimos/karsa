<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerPayment extends Model
{
    use BelongsToBusiness;

    protected $fillable = ['business_id', 'sale_id', 'amount', 'payment_date', 'payment_method', 'notes', 'created_by'];

    protected $casts = [
        'amount' => 'double',
        'payment_date' => 'date',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
