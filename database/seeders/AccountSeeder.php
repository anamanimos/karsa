<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $business = \App\Models\Business::first();
        if ($business) {
            session(['active_business_id' => $business->id]);
        }

        $accounts = [
            // Assets (Aktiva)
            ['code' => '1001', 'name' => 'Kas Toko / Kasir', 'type' => 'asset', 'subtype' => 'cash_bank', 'initial_balance' => 1000000, 'description' => 'Uang tunai di laci kasir'],
            ['code' => '1002', 'name' => 'Kas Utama / Brankas', 'type' => 'asset', 'subtype' => 'cash_bank', 'initial_balance' => 5000000, 'description' => 'Kas tunai operasional utama'],
            ['code' => '1003', 'name' => 'Bank BCA', 'type' => 'asset', 'subtype' => 'cash_bank', 'initial_balance' => 10000000, 'description' => 'Rekening operasional bank BCA'],
            ['code' => '1101', 'name' => 'Piutang Usaha', 'type' => 'asset', 'subtype' => 'receivable', 'initial_balance' => 0, 'description' => 'Tagihan piutang penjualan ke pelanggan'],
            ['code' => '1201', 'name' => 'Persediaan Barang Dagang', 'type' => 'asset', 'subtype' => 'inventory', 'initial_balance' => 0, 'description' => 'Nilai persediaan stok barang'],

            // Liabilities (Kewajiban / Hutang)
            ['code' => '2001', 'name' => 'Hutang Usaha (Supplier)', 'type' => 'liability', 'subtype' => 'payable', 'initial_balance' => 0, 'description' => 'Kewajiban hutang pembelian ke supplier/pemasok'],
            ['code' => '2002', 'name' => 'Hutang Gaji & Komisi', 'type' => 'liability', 'subtype' => 'payable', 'initial_balance' => 0, 'description' => 'Akumulasi beban gaji karyawan yang belum dibayar'],

            // Equity (Modal)
            ['code' => '3001', 'name' => 'Modal Pemilik', 'type' => 'equity', 'subtype' => 'equity', 'initial_balance' => 16000000, 'description' => 'Modal awal disetor pemilik usaha'],
            ['code' => '3002', 'name' => 'Laba Ditahan', 'type' => 'equity', 'subtype' => 'equity', 'initial_balance' => 0, 'description' => 'Akumulasi laba periode berjalan'],

            // Revenue (Pendapatan)
            ['code' => '4001', 'name' => 'Pendapatan Penjualan (POS)', 'type' => 'revenue', 'subtype' => 'operating_revenue', 'initial_balance' => 0, 'description' => 'Omset hasil transaksi penjualan'],
            ['code' => '4101', 'name' => 'Pendapatan Lain-lain', 'type' => 'revenue', 'subtype' => 'other_revenue', 'initial_balance' => 0, 'description' => 'Pendapatan jasa atau non-operasional'],

            // Cost of Goods Sold (HPP)
            ['code' => '5001', 'name' => 'Harga Pokok Penjualan (HPP)', 'type' => 'expense', 'subtype' => 'cogs', 'initial_balance' => 0, 'description' => 'Biaya modal perolehan barang yang terjual'],

            // Operating Expenses (Beban Operasional)
            ['code' => '6001', 'name' => 'Beban Gaji & Bonus Karyawan', 'type' => 'expense', 'subtype' => 'operating_expense', 'initial_balance' => 0, 'description' => 'Penggajian, tunjangan, dan bonus SDM'],
            ['code' => '6002', 'name' => 'Beban Sewa & Operasional Toko', 'type' => 'expense', 'subtype' => 'operating_expense', 'initial_balance' => 0, 'description' => 'Sewa tempat, pemeliharaan, kebersihan'],
            ['code' => '6003', 'name' => 'Beban Listrik, Air & Internet', 'type' => 'expense', 'subtype' => 'operating_expense', 'initial_balance' => 0, 'description' => 'Tagihan utilitas bulanan'],
            ['code' => '6004', 'name' => 'Beban Pemasaran & Promosi', 'type' => 'expense', 'subtype' => 'operating_expense', 'initial_balance' => 0, 'description' => 'Biaya iklan, brosur, diskon khusus'],
            ['code' => '6005', 'name' => 'Beban Kerusakan / Selisih Stok', 'type' => 'expense', 'subtype' => 'operating_expense', 'initial_balance' => 0, 'description' => 'Kerugian akibat barang rusak, expired, selisih opname'],
            ['code' => '6099', 'name' => 'Beban Lain-lain', 'type' => 'expense', 'subtype' => 'other_expense', 'initial_balance' => 0, 'description' => 'Beban operasional umum lainnya'],
        ];

        foreach ($accounts as $acc) {
            Account::firstOrCreate(['code' => $acc['code']], $acc);
        }
    }
}
