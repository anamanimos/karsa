<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // superadmin, user, kasir
        'plan_id',
        'custom_max_businesses',
        'custom_max_employees',
        'active_business_id',
        'phone',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'plan_id' => 'integer',
            'custom_max_businesses' => 'integer',
            'custom_max_employees' => 'integer',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Maximum businesses allowed for this user.
     */
    public function maxBusinesses(): int
    {
        if ($this->isSuperAdmin()) {
            return 999999;
        }

        if ($this->custom_max_businesses !== null) {
            return (int) $this->custom_max_businesses;
        }

        if ($this->plan) {
            return (int) $this->plan->max_businesses;
        }

        return 1;
    }

    /**
     * Check if user can create another business.
     */
    public function canCreateBusiness(): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->ownedBusinesses()->count() < $this->maxBusinesses();
    }

    /**
     * Maximum active employees allowed per business.
     */
    public function maxEmployeesPerBusiness(): int
    {
        if ($this->isSuperAdmin()) {
            return 999999;
        }

        if ($this->custom_max_employees !== null) {
            return (int) $this->custom_max_employees;
        }

        if ($this->plan) {
            return (int) $this->plan->max_employees_per_business;
        }

        return 3;
    }

    /**
     * Check if an active business owned by this user can add another employee.
     */
    public function canCreateEmployee(?Business $business = null): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $targetBusiness = $business ?? $this->activeBusiness;
        if (!$targetBusiness) {
            return false;
        }

        $activeEmployeesCount = $targetBusiness->employees()->where('is_active', true)->count();
        return $activeEmployeesCount < $this->maxEmployeesPerBusiness();
    }

    public function ownedBusinesses(): HasMany
    {
        return $this->hasMany(Business::class, 'owner_id');
    }

    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class, 'business_users')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function activeBusiness(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'active_business_id');
    }

    public function allBusinesses(): Collection
    {
        if ($this->isSuperAdmin()) {
            return Business::orderBy('name')->get();
        }

        $owned = $this->ownedBusinesses()->get();
        $member = $this->businesses()->get();

        return $owned->merge($member)->unique('id')->values();
    }

    public function switchBusiness(int|Business $business): bool
    {
        $businessId = $business instanceof Business ? $business->id : $business;
        $targetBusiness = Business::find($businessId);

        if (!$targetBusiness || !$targetBusiness->is_active) {
            return false;
        }

        if ($this->isSuperAdmin() || $this->ownedBusinesses()->where('id', $businessId)->exists() || $this->businesses()->where('businesses.id', $businessId)->exists()) {
            $this->update(['active_business_id' => $businessId]);
            return true;
        }

        return false;
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'created_by');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'created_by');
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function cashRegisters(): HasMany
    {
        return $this->hasMany(CashRegister::class);
    }
}
