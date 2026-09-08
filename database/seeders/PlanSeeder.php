<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $starter = Plan::updateOrCreate(
            ['slug' => 'starter'],
            [
                'name' => 'Starter (Gratis)',
                'description' => 'Ideal untuk pelaku usaha mikro atau rintisan yang baru memulai toko pertama.',
                'price' => 0,
                'billing_cycle' => 'monthly',
                'max_businesses' => 1,
                'max_employees_per_business' => 3,
                'max_products_per_business' => 100,
                'features' => [
                    '1 Unit Usaha / Toko',
                    'Maksimal 3 Karyawan per Toko',
                    'Maksimal 100 Produk Master',
                    'POS Kasir & Cetak Struk',
                    'Stok Opname Sederhana',
                ],
                'is_default' => true,
                'is_active' => true,
            ]
        );

        $pro = Plan::updateOrCreate(
            ['slug' => 'pro'],
            [
                'name' => 'Pro Bisnis (Tumbuh)',
                'description' => 'Sangat cocok untuk bisnis berkembang dengan beberapa cabang dan staf kasir.',
                'price' => 99000,
                'billing_cycle' => 'monthly',
                'max_businesses' => 3,
                'max_employees_per_business' => 15,
                'max_products_per_business' => 1000,
                'features' => [
                    'Hingga 3 Unit Usaha / Cabang',
                    'Maksimal 15 Karyawan per Toko',
                    'Hingga 1.000 Produk Master',
                    'Laporan Laba Rugi & Arus Kas',
                    'Sistem Penggajian & Slip Gaji',
                    'Manajemen Hutang & Piutang',
                ],
                'is_default' => false,
                'is_active' => true,
            ]
        );

        $enterprise = Plan::updateOrCreate(
            ['slug' => 'enterprise'],
            [
                'name' => 'Enterprise (Unlimited)',
                'description' => 'Solusi komprehensif tanpa batas untuk jaringan bisnis multi-cabang atau waralaba.',
                'price' => 299000,
                'billing_cycle' => 'monthly',
                'max_businesses' => 999999,
                'max_employees_per_business' => 999999,
                'max_products_per_business' => 999999,
                'features' => [
                    'Tanpa Batas Unit Usaha (Unlimited)',
                    'Tanpa Batas Karyawan (Unlimited)',
                    'Tanpa Batas Produk (Unlimited)',
                    'Seluruh Laporan Akuntansi Lengkap',
                    'Dukungan Prioritas Platform',
                ],
                'is_default' => false,
                'is_active' => true,
            ]
        );

        // Assign plans to existing seed users
        User::where('role', 'superadmin')->update(['plan_id' => $enterprise->id]);
        User::where('email', 'admin@postani.com')->update(['plan_id' => $pro->id]);
        User::where('email', 'budi@kopinusantara.com')->update(['plan_id' => $starter->id]);
        User::whereNull('plan_id')->where('role', '!=', 'superadmin')->update(['plan_id' => $starter->id]);
    }
}
