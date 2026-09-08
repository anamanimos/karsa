<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_cycle',
        'max_businesses',
        'max_employees_per_business',
        'max_products_per_business',
        'features',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'price' => 'double',
        'max_businesses' => 'integer',
        'max_employees_per_business' => 'integer',
        'max_products_per_business' => 'integer',
        'features' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public static function defaultPlan(): ?self
    {
        return static::where('is_default', true)->where('is_active', true)->first()
            ?? static::where('slug', 'starter')->first()
            ?? static::first();
    }

    public function isUnlimitedBusinesses(): bool
    {
        return $this->max_businesses >= 999999 || $this->max_businesses < 0;
    }

    public function isUnlimitedEmployees(): bool
    {
        return $this->max_employees_per_business >= 999999 || $this->max_employees_per_business < 0;
    }
}
