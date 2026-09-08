<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class BusinessSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'company_name', 'value' => 'KarsaERP', 'description' => 'Nama bisnis / usaha Anda'],
            ['key' => 'business_type', 'value' => 'general', 'description' => 'Jenis bisnis (general, retail, fnb, service, etc)'],
            ['key' => 'company_phone', 'value' => '0812-3456-7890', 'description' => 'Nomor telepon usaha'],
            ['key' => 'company_email', 'value' => 'info@karsaerp.com', 'description' => 'Email kontak usaha'],
            ['key' => 'company_address', 'value' => 'Jl. Bisnis Raya No. 123, Jakarta', 'description' => 'Alamat lengkap usaha'],
            ['key' => 'currency_symbol', 'value' => 'Rp', 'description' => 'Simbol mata uang'],
            ['key' => 'tax_percentage', 'value' => '0', 'description' => 'Persentase Pajak PPN default (%)'],
            ['key' => 'receipt_footer', 'value' => 'Terima kasih atas kunjungan Anda!', 'description' => 'Pesan catatan kaki pada struk belanja'],
            ['key' => 'min_margin', 'value' => '1000', 'description' => 'Margin minimum per produk (Rupiah)'],
            ['key' => 'require_shift_for_pos', 'value' => '0', 'description' => 'Wajibkan buka shift kasir sebelum transaksi POS (1: Ya, 0: Tidak)'],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], $s);
        }
    }
}
