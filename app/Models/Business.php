<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'business_type',
        'phone',
        'email',
        'address',
        'city',
        'postal_code',
        'tax_id',
        'currency',
        'timezone',
        'currency_symbol',
        'tax_percentage',
        'min_margin',
        'require_shift_for_pos',
        'receipt_footer',
        'telegram_bot_token',
        'telegram_chat_id',
        'is_active',
    ];

    protected $casts = [
        'tax_percentage' => 'double',
        'min_margin' => 'double',
        'require_shift_for_pos' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'business_users')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function cashRegisters(): HasMany
    {
        return $this->hasMany(CashRegister::class);
    }

    /**
     * Initialize standard default data (Units & Chart of Accounts) for this business.
     */
    public function initializeDefaults(): void
    {
        // 1. Standard Units
        $defaultUnits = [
            ['name' => 'Pieces / Buah', 'symbol' => 'Pcs'],
            ['name' => 'Kilogram', 'symbol' => 'Kg'],
            ['name' => 'Gram', 'symbol' => 'Gr'],
            ['name' => 'Liter', 'symbol' => 'L'],
            ['name' => 'Mililiter', 'symbol' => 'Ml'],
            ['name' => 'Dus / Box', 'symbol' => 'Box'],
            ['name' => 'Pak / Bungkus', 'symbol' => 'Pack'],
            ['name' => 'Karung / Sack', 'symbol' => 'Sak'],
            ['name' => 'Botol', 'symbol' => 'Btl'],
            ['name' => 'Porsi / Cup', 'symbol' => 'Cup'],
        ];

        foreach ($defaultUnits as $u) {
            Unit::firstOrCreate([
                'business_id' => $this->id,
                'symbol' => $u['symbol'],
            ], [
                'name' => $u['name'],
            ]);
        }

        // 2. Standard Chart of Accounts (COA)
        $defaultAccounts = [
            ['code' => '1-1001', 'name' => 'Kas Toko / Kasir', 'type' => 'asset', 'subtype' => 'cash_bank', 'initial_balance' => 0],
            ['code' => '1-1002', 'name' => 'Kas Brankas Utama', 'type' => 'asset', 'subtype' => 'cash_bank', 'initial_balance' => 0],
            ['code' => '1-1003', 'name' => 'Rekening Bank Operasional', 'type' => 'asset', 'subtype' => 'cash_bank', 'initial_balance' => 0],
            ['code' => '1-1100', 'name' => 'Piutang Usaha (Pelanggan)', 'type' => 'asset', 'subtype' => 'receivable', 'initial_balance' => 0],
            ['code' => '1-1200', 'name' => 'Persediaan Barang Dagang', 'type' => 'asset', 'subtype' => 'inventory', 'initial_balance' => 0],
            ['code' => '2-1001', 'name' => 'Hutang Usaha (Supplier)', 'type' => 'liability', 'subtype' => 'payable', 'initial_balance' => 0],
            ['code' => '3-1001', 'name' => 'Modal Disetor Pemilik', 'type' => 'equity', 'subtype' => 'equity', 'initial_balance' => 0],
            ['code' => '3-2001', 'name' => 'Laba Ditahan / Berjalan', 'type' => 'equity', 'subtype' => 'retained_earnings', 'initial_balance' => 0],
            ['code' => '4-1001', 'name' => 'Pendapatan Penjualan', 'type' => 'revenue', 'subtype' => 'operating_revenue', 'initial_balance' => 0],
            ['code' => '4-2001', 'name' => 'Pendapatan Jasa & Lain-lain', 'type' => 'revenue', 'subtype' => 'other_revenue', 'initial_balance' => 0],
            ['code' => '5-1001', 'name' => 'Beban Pokok Penjualan (HPP)', 'type' => 'cogs', 'subtype' => 'cogs', 'initial_balance' => 0],
            ['code' => '6-1001', 'name' => 'Beban Gaji, Upah & Bonus Karyawan', 'type' => 'expense', 'subtype' => 'operating_expense', 'initial_balance' => 0],
            ['code' => '6-1002', 'name' => 'Beban Listrik, Air & Internet', 'type' => 'expense', 'subtype' => 'operating_expense', 'initial_balance' => 0],
            ['code' => '6-1003', 'name' => 'Beban Sewa Tempat & Bangunan', 'type' => 'expense', 'subtype' => 'operating_expense', 'initial_balance' => 0],
            ['code' => '6-1004', 'name' => 'Beban Operasional & Pemeliharaan', 'type' => 'expense', 'subtype' => 'operating_expense', 'initial_balance' => 0],
            ['code' => '6-1005', 'name' => 'Beban Transportasi & Logistik', 'type' => 'expense', 'subtype' => 'operating_expense', 'initial_balance' => 0],
            ['code' => '6-1006', 'name' => 'Beban Pemasaran & Promosi', 'type' => 'expense', 'subtype' => 'operating_expense', 'initial_balance' => 0],
            ['code' => '6-9999', 'name' => 'Beban Lain-lain', 'type' => 'expense', 'subtype' => 'other_expense', 'initial_balance' => 0],
        ];

        foreach ($defaultAccounts as $acc) {
            Account::firstOrCreate([
                'business_id' => $this->id,
                'code' => $acc['code'],
            ], [
                'name' => $acc['name'],
                'type' => $acc['type'],
                'subtype' => $acc['subtype'],
                'initial_balance' => $acc['initial_balance'],
                'is_active' => true,
            ]);
        }

        // 3. Default Categories
        $defaultCategories = [
            'Umum / Lainnya',
            'Produk Utama',
            'Paket & Promo',
        ];

        foreach ($defaultCategories as $catName) {
            Category::firstOrCreate([
                'business_id' => $this->id,
                'name' => $catName,
            ], [
                'description' => 'Kategori produk bawaan',
            ]);
        }
    }
}
