<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Sale extends Model
{
    use SoftDeletes, BelongsToBusiness;
    
    protected $fillable = [
        'business_id',
        'invoice_number',
        'customer_id',
        'sale_date',
        'total_amount',
        'discount_amount',
        'tax_amount',
        'payment_method',
        'payment_status',
        'paid_amount',
        'due_amount',
        'cash_register_id',
        'cashier_employee_id',
        'created_by',
    ];

    protected $casts = [
        'sale_date' => 'datetime',
        'total_amount' => 'double',
        'discount_amount' => 'double',
        'tax_amount' => 'double',
        'paid_amount' => 'double',
        'due_amount' => 'double',
    ];

    public static function generateInvoiceNumber(): string
    {
        $today = Carbon::today()->format('Ymd');
        $prefix = "INV-" . $today . "-";
        
        $lastSale = self::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastSale) {
            $lastNum = intval(substr($lastSale->invoice_number, -4));
            $nextNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNum = '0001';
        }

        return $prefix . $nextNum;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function customerPayments(): HasMany
    {
        return $this->hasMany(CustomerPayment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'cashier_employee_id');
    }
}
