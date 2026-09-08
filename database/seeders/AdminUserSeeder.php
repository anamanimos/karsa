<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Business;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin Platform
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@saaserp.com'],
            [
                'name' => 'Super Administrator SaaS',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'is_active' => true,
                'phone' => '08111111111'
            ]
        );

        // 2. Owner Akun 1: Toko Tani & Agribisnis
        $owner = User::firstOrCreate(
            ['email' => 'admin@postani.com'],
            [
                'name' => 'Owner Toko Tani',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
                'phone' => '081234567890'
            ]
        );

        $business = Business::firstOrCreate(
            ['name' => 'Toko Tani & Agribisnis Jaya'],
            [
                'owner_id' => $owner->id,
                'email' => 'contact@tokotani.com',
                'phone' => '081234567890',
                'address' => 'Jl. Raya Pertanian Makmur No. 12',
                'city' => 'Malang',
                'postal_code' => '65145',
                'business_type' => 'Retail & Agribisnis',
                'currency' => 'IDR',
                'timezone' => 'Asia/Jakarta',
                'is_active' => true,
            ]
        );

        $business->initializeDefaults();

        $owner->update(['active_business_id' => $business->id]);
        $owner->businesses()->syncWithoutDetaching([
            $business->id => ['role' => 'owner']
        ]);

        // 3. Kasir Toko Tani
        $kasir = User::firstOrCreate(
            ['email' => 'kasir@postani.com'],
            [
                'name' => 'Siti Kasir',
                'password' => Hash::make('password'),
                'role' => 'kasir',
                'is_active' => true,
                'phone' => '081298765432',
                'active_business_id' => $business->id,
            ]
        );

        $kasir->businesses()->syncWithoutDetaching([
            $business->id => ['role' => 'cashier']
        ]);

        // Also set superadmin's default active business to this business for instant preview
        if (!$superadmin->active_business_id) {
            $superadmin->update(['active_business_id' => $business->id]);
        }

        // 4. Tenant 2: Kopi Nusantara (for demonstrating multi-tenant isolation)
        $owner2 = User::firstOrCreate(
            ['email' => 'budi@kopinusantara.com'],
            [
                'name' => 'Budi Barista',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
                'phone' => '081399887766'
            ]
        );

        $business2 = Business::firstOrCreate(
            ['name' => 'Kopi Nusantara & Cafe'],
            [
                'owner_id' => $owner2->id,
                'email' => 'halo@kopinusantara.com',
                'phone' => '081399887766',
                'address' => 'Jl. Senopati No. 88',
                'city' => 'Jakarta Selatan',
                'postal_code' => '12190',
                'business_type' => 'F&B & Coffee Shop',
                'currency' => 'IDR',
                'timezone' => 'Asia/Jakarta',
                'is_active' => true,
            ]
        );

        $business2->initializeDefaults();

        $owner2->update(['active_business_id' => $business2->id]);
        $owner2->businesses()->syncWithoutDetaching([
            $business2->id => ['role' => 'owner']
        ]);
    }
}
