<?php

namespace App\Helpers;

use App\Models\Business;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TenantHelper
{
    /**
     * Get current active business for authenticated user.
     */
    public static function currentBusiness(): ?Business
    {
        // 1. Check session first if set (useful during seeders/tests or switched context)
        if (session()->has('active_business_id')) {
            $sessBiz = Business::find(session('active_business_id'));
            if ($sessBiz) {
                return $sessBiz;
            }
        }

        $user = Auth::user();
        if (!$user) {
            return null;
        }

        if ($user->active_business_id) {
            return $user->activeBusiness;
        }

        // Fallback to first owned or associated business
        $biz = $user->ownedBusinesses()->first() ?? $user->businesses()->first();
        if ($biz) {
            $user->update(['active_business_id' => $biz->id]);
            return $biz;
        }

        return null;
    }

    /**
     * Get active business ID.
     */
    public static function currentBusinessId(): ?int
    {
        return self::currentBusiness()?->id;
    }

    /**
     * Check if currently authenticated user is Super Admin.
     */
    public static function isSuperAdmin(): bool
    {
        return Auth::check() && Auth::user()->role === 'superadmin';
    }
}

// Global procedural helper functions
if (!function_exists('currentBusiness')) {
    function currentBusiness(): ?Business
    {
        return TenantHelper::currentBusiness();
    }
}

if (!function_exists('currentBusinessId')) {
    function currentBusinessId(): ?int
    {
        return TenantHelper::currentBusinessId();
    }
}

if (!function_exists('isSuperAdmin')) {
    function isSuperAdmin(): bool
    {
        return TenantHelper::isSuperAdmin();
    }
}
