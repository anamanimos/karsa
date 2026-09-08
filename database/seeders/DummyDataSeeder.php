<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\SupplierPayment;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\CustomerPayment;
use App\Models\CashTransaction;
use App\Models\CashRegister;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Ensure default business is loaded and active in session
        $business = \App\Models\Business::first();
        $businessId = $business?->id ?? 1;
        session(['active_business_id' => $businessId]);

        // 1. Ensure Users exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@postani.com'],
            [
                'name' => 'Owner KarsaERP',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
                'phone' => '081234567890',
                'active_business_id' => $businessId,
            ]
        );

        $kasir = User::firstOrCreate(
            ['email' => 'kasir@postani.com'],
            [
                'name' => 'Siti Kasir',
                'password' => Hash::make('password'),
                'role' => 'kasir',
                'is_active' => true,
                'phone' => '081298765432',
                'active_business_id' => $businessId,
            ]
        );

        // Accounts
        $kasirAccount = Account::where('code', '1001')->first();
        $kasUtamaAccount = Account::where('code', '1002')->first();
        $bcaAccount = Account::where('code', '1003')->first();

        // 2. Units
        $unitKg = Unit::firstOrCreate(['symbol' => 'kg'], ['business_id' => $businessId, 'name' => 'Kilogram', 'symbol' => 'kg']);
        $unitKarung = Unit::firstOrCreate(['symbol' => 'karung'], ['business_id' => $businessId, 'name' => 'Karung', 'symbol' => 'karung']);
        $unitSak = Unit::firstOrCreate(['symbol' => 'sak'], ['business_id' => $businessId, 'name' => 'Sak', 'symbol' => 'sak']);
        $unitLiter = Unit::firstOrCreate(['symbol' => 'liter'], ['business_id' => $businessId, 'name' => 'Liter', 'symbol' => 'liter']);
        $unitBotol = Unit::firstOrCreate(['symbol' => 'botol'], ['business_id' => $businessId, 'name' => 'Botol', 'symbol' => 'botol']);
        $unitPcs = Unit::firstOrCreate(['symbol' => 'pcs'], ['business_id' => $businessId, 'name' => 'Pieces / Buah', 'symbol' => 'pcs']);
        $unitPack = Unit::firstOrCreate(['symbol' => 'pack'], ['business_id' => $businessId, 'name' => 'Pack', 'symbol' => 'pack']);
        $unitBox = Unit::firstOrCreate(['symbol' => 'box'], ['business_id' => $businessId, 'name' => 'Box / Dus', 'symbol' => 'box']);
        $unitRoll = Unit::firstOrCreate(['symbol' => 'roll'], ['business_id' => $businessId, 'name' => 'Roll', 'symbol' => 'roll']);
        $unitLusin = Unit::firstOrCreate(['symbol' => 'lsn'], ['business_id' => $businessId, 'name' => 'Lusin', 'symbol' => 'lsn']);

        // 3. Categories
        $catPupuk = Category::firstOrCreate(['name' => 'Pupuk & Nutrisi Tanaman'], ['business_id' => $businessId, 'name' => 'Pupuk & Nutrisi Tanaman', 'description' => 'Pupuk kimia, organik, dan nutrisi cair']);
        $catBenih = Category::firstOrCreate(['name' => 'Benih & Bibit Unggul'], ['business_id' => $businessId, 'name' => 'Benih & Bibit Unggul', 'description' => 'Benih tanaman pangan, hortikultura, dan sayur']);
        $catPestisida = Category::firstOrCreate(['name' => 'Pestisida & Proteksi Tanaman'], ['business_id' => $businessId, 'name' => 'Pestisida & Proteksi Tanaman', 'description' => 'Herbisida, insektisida, dan fungisida tanaman']);
        $catAlat = Category::firstOrCreate(['name' => 'Alat & Mesin Pertanian'], ['business_id' => $businessId, 'name' => 'Alat & Mesin Pertanian', 'description' => 'Sprayer, perkakas baja, selang, dan perlengkapan safety']);
        $catSembako = Category::firstOrCreate(['name' => 'Sembako & Kebutuhan Retail'], ['business_id' => $businessId, 'name' => 'Sembako & Kebutuhan Retail', 'description' => 'Beras premium, minyak goreng, gula, dan aneka kopi']);

        // 4. Suppliers
        $suppliers = [
            Supplier::firstOrCreate(['name' => 'PT Pupuk Indonesia (Persero)'], ['business_id' => $businessId, 'phone' => '021-5432100', 'address' => 'Jl. Taman Anggrek No. 12, Jakarta', 'notes' => 'Supplier resmi pupuk bersubsidi dan non-subsidi']),
            Supplier::firstOrCreate(['name' => 'CV Tani Makmur Sejahtera'], ['business_id' => $businessId, 'phone' => '0341-778899', 'address' => 'Jl. Raya Agro No. 45, Malang', 'notes' => 'Distributor pestisida, herbisida, dan obat hama']),
            Supplier::firstOrCreate(['name' => 'PT Syngenta Indonesia'], ['business_id' => $businessId, 'phone' => '021-7890123', 'address' => 'Kawasan Industri Cikarang, Bekasi', 'notes' => 'Produsen benih berkualitas tinggi dan pestisida terpercaya']),
            Supplier::firstOrCreate(['name' => 'PT BISI International Tbk'], ['business_id' => $businessId, 'phone' => '0354-667788', 'address' => 'Jl. Industri No. 8, Kediri', 'notes' => 'Supplier benih jagung hibrida dan sayuran unggul']),
            Supplier::firstOrCreate(['name' => 'Distributor Sembako Nusantara'], ['business_id' => $businessId, 'phone' => '021-8899001', 'address' => 'Jl. Pergudangan Indah No. 5, Sidoarjo', 'notes' => 'Pemasok beras, minyak goreng kemasan, dan gula']),
        ];

        // 5. Customers
        $customers = [
            Customer::firstOrCreate(['phone' => '081334455667'], ['business_id' => $businessId, 'name' => 'Pak Slamet (Kelompok Tani Subur)', 'address' => 'Desa Sumbersekar RT 02 RW 01']),
            Customer::firstOrCreate(['phone' => '085211223344'], ['business_id' => $businessId, 'name' => 'Ibu Sri Rahayu', 'address' => 'Dusun Krajan No. 14']),
            Customer::firstOrCreate(['phone' => '087855667788'], ['business_id' => $businessId, 'name' => 'Pak Bambang Utomo', 'address' => 'Jl. Sawah Indah No. 8']),
            Customer::firstOrCreate(['phone' => '089900112233'], ['business_id' => $businessId, 'name' => 'Pak Haji Mahmud (Toko Tani Mandiri)', 'address' => 'Kecamatan Tegalrejo No. 88']),
            Customer::firstOrCreate(['phone' => '081233449988'], ['business_id' => $businessId, 'name' => 'Ibu Siti Aisyah', 'address' => 'Desa Karangpandan RT 05']),
        ];

        // 6. Products (19 Produk Lengkap & Realistis)
        $productsData = [
            // Pupuk
            [
                'sku' => 'PRD-001',
                'name' => 'Pupuk Urea Subur 50kg',
                'category_id' => $catPupuk->id,
                'buy_unit_id' => $unitKarung->id,
                'sell_unit_id' => $unitKarung->id,
                'conversion_factor' => 1,
                'last_purchase_price' => 150000,
                'avg_purchase_price' => 150000,
                'selling_price' => 165000,
                'stock' => 65,
                'min_stock' => 10,
                'notes' => 'Pupuk Nitrogen 46% untuk percepatan daun & batang',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-002',
                'name' => 'Pupuk NPK Phonska Plus 15-15-15 50kg',
                'category_id' => $catPupuk->id,
                'buy_unit_id' => $unitKarung->id,
                'sell_unit_id' => $unitKarung->id,
                'conversion_factor' => 1,
                'last_purchase_price' => 175000,
                'avg_purchase_price' => 175000,
                'selling_price' => 195000,
                'stock' => 45,
                'min_stock' => 10,
                'notes' => 'Pupuk majemuk seimbang NPK + Sulfur & Zink',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-003',
                'name' => 'Pupuk Organik Kompos Super 25kg',
                'category_id' => $catPupuk->id,
                'buy_unit_id' => $unitSak->id,
                'sell_unit_id' => $unitSak->id,
                'conversion_factor' => 1,
                'last_purchase_price' => 35000,
                'avg_purchase_price' => 35000,
                'selling_price' => 45000,
                'stock' => 80,
                'min_stock' => 15,
                'notes' => 'Kompos fermentasi kotoran ternak & mikroba pengurai',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-004',
                'name' => 'Pupuk Cair Hayati GDM Organik 1 Liter',
                'category_id' => $catPupuk->id,
                'buy_unit_id' => $unitBotol->id,
                'sell_unit_id' => $unitBotol->id,
                'conversion_factor' => 1,
                'last_purchase_price' => 75000,
                'avg_purchase_price' => 75000,
                'selling_price' => 95000,
                'stock' => 30,
                'min_stock' => 5,
                'notes' => 'Booster tanaman pangan & buah rasa manis',
                'is_active' => true,
            ],

            // Benih
            [
                'sku' => 'PRD-005',
                'name' => 'Benih Jagung Hibrida BISI-18 (1kg)',
                'category_id' => $catBenih->id,
                'buy_unit_id' => $unitPack->id,
                'sell_unit_id' => $unitPack->id,
                'conversion_factor' => 1,
                'last_purchase_price' => 85000,
                'avg_purchase_price' => 85000,
                'selling_price' => 105000,
                'stock' => 50,
                'min_stock' => 8,
                'notes' => 'Tongkol ganda, potensi panen 12 ton/ha',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-006',
                'name' => 'Benih Padi Ciherang Unggul Super (5kg)',
                'category_id' => $catBenih->id,
                'buy_unit_id' => $unitSak->id,
                'sell_unit_id' => $unitSak->id,
                'conversion_factor' => 1,
                'last_purchase_price' => 65000,
                'avg_purchase_price' => 65000,
                'selling_price' => 80000,
                'stock' => 45,
                'min_stock' => 10,
                'notes' => 'Beras pulen harum, tahan wereng coklat',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-007',
                'name' => 'Benih Cabe Rawit Merah Unggul (10gr)',
                'category_id' => $catBenih->id,
                'buy_unit_id' => $unitPack->id,
                'sell_unit_id' => $unitPack->id,
                'conversion_factor' => 1,
                'last_purchase_price' => 28000,
                'avg_purchase_price' => 28000,
                'selling_price' => 38000,
                'stock' => 60,
                'min_stock' => 10,
                'notes' => 'Tahan patek antraknosa, buah lebat & pedas',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-008',
                'name' => 'Benih Tomat Sayur F1 Tymoti (10gr)',
                'category_id' => $catBenih->id,
                'buy_unit_id' => $unitPack->id,
                'sell_unit_id' => $unitPack->id,
                'conversion_factor' => 1,
                'last_purchase_price' => 32000,
                'avg_purchase_price' => 32000,
                'selling_price' => 42000,
                'stock' => 40,
                'min_stock' => 5,
                'notes' => 'Buah keras tahan angkut jarak jauh',
                'is_active' => true,
            ],

            // Pestisida
            [
                'sku' => 'PRD-009',
                'name' => 'Herbisida Roundup 486 SL (1 Liter)',
                'category_id' => $catPestisida->id,
                'buy_unit_id' => $unitBotol->id,
                'sell_unit_id' => $unitBotol->id,
                'conversion_factor' => 1,
                'last_purchase_price' => 95000,
                'avg_purchase_price' => 95000,
                'selling_price' => 115000,
                'stock' => 35,
                'min_stock' => 5,
                'notes' => 'Herbisida purna tumbuh sistemik babat rumput tuntas',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-010',
                'name' => 'Insektisida Gramoxone 276 SL (1 Liter)',
                'category_id' => $catPestisida->id,
                'buy_unit_id' => $unitBotol->id,
                'sell_unit_id' => $unitBotol->id,
                'conversion_factor' => 1,
                'last_purchase_price' => 70000,
                'avg_purchase_price' => 70000,
                'selling_price' => 88000,
                'stock' => 30,
                'min_stock' => 5,
                'notes' => 'Herbisida kontak cepat hangus gulma kebun',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-011',
                'name' => 'Fungisida Antracol 70 WP (500gr)',
                'category_id' => $catPestisida->id,
                'buy_unit_id' => $unitPack->id,
                'sell_unit_id' => $unitPack->id,
                'conversion_factor' => 1,
                'last_purchase_price' => 55000,
                'avg_purchase_price' => 55000,
                'selling_price' => 68000,
                'stock' => 28,
                'min_stock' => 5,
                'notes' => 'Pengendali jamur busuk daun & busuk batang',
                'is_active' => true,
            ],

            // Alat
            [
                'sku' => 'PRD-012',
                'name' => 'Tangki Semprot Elektrik DGW 16 Liter',
                'category_id' => $catAlat->id,
                'buy_unit_id' => $unitPcs->id,
                'sell_unit_id' => $unitPcs->id,
                'conversion_factor' => 1,
                'last_purchase_price' => 420000,
                'avg_purchase_price' => 420000,
                'selling_price' => 520000,
                'stock' => 12,
                'min_stock' => 2,
                'notes' => 'Sprayer baterai 12V 8Ah + manual 2 in 1',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-013',
                'name' => 'Cangkul Baja Super Cap Buaya',
                'category_id' => $catAlat->id,
                'buy_unit_id' => $unitPcs->id,
                'sell_unit_id' => $unitPcs->id,
                'conversion_factor' => 1,
                'last_purchase_price' => 65000,
                'avg_purchase_price' => 65000,
                'selling_price' => 85000,
                'stock' => 25,
                'min_stock' => 3,
                'notes' => 'Baja per asli tebal tajam tahan batu',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-014',
                'name' => 'Selang Air Tebal Benang Transparan 50m',
                'category_id' => $catAlat->id,
                'buy_unit_id' => $unitRoll->id,
                'sell_unit_id' => $unitRoll->id,
                'conversion_factor' => 1,
                'last_purchase_price' => 180000,
                'avg_purchase_price' => 180000,
                'selling_price' => 235000,
                'stock' => 10,
                'min_stock' => 2,
                'notes' => 'Selang elastis anti pecah & anti lumut',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-015',
                'name' => 'Sarung Tangan Karet Safety Heavy Duty',
                'category_id' => $catAlat->id,
                'buy_unit_id' => $unitLusin->id,
                'sell_unit_id' => $unitPcs->id,
                'conversion_factor' => 12,
                'last_purchase_price' => 60000, // per lusin
                'avg_purchase_price' => 60000,
                'selling_price' => 7500, // per pasang
                'stock' => 36, // pasang
                'min_stock' => 6,
                'notes' => 'Beli per lusin, jual per pasang (konversi 12)',
                'is_active' => true,
            ],

            // Sembako & Ritel
            [
                'sku' => 'PRD-016',
                'name' => 'Beras Rojolele Premium Super (5kg)',
                'category_id' => $catSembako->id,
                'buy_unit_id' => $unitKarung->id,
                'sell_unit_id' => $unitKarung->id,
                'conversion_factor' => 1,
                'last_purchase_price' => 68000,
                'avg_purchase_price' => 68000,
                'selling_price' => 78000,
                'stock' => 40,
                'min_stock' => 5,
                'notes' => 'Beras putih bersih, pulen wangi pandan',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-017',
                'name' => 'Minyak Goreng Sawit Kemasan 2 Liter',
                'category_id' => $catSembako->id,
                'buy_unit_id' => $unitBox->id,
                'sell_unit_id' => $unitBotol->id,
                'conversion_factor' => 6,
                'last_purchase_price' => 90000, // 1 box = 6 pouch
                'avg_purchase_price' => 90000,
                'selling_price' => 17500, // per pouch
                'stock' => 48,
                'min_stock' => 12,
                'notes' => 'Beli per dus isi 6, jual per botol/pouch',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-018',
                'name' => 'Gula Pasir Kristal Putih Premium 1kg',
                'category_id' => $catSembako->id,
                'buy_unit_id' => $unitSak->id,
                'sell_unit_id' => $unitPack->id,
                'conversion_factor' => 50,
                'last_purchase_price' => 750000, // per sak 50kg
                'avg_purchase_price' => 750000,
                'selling_price' => 17000, // per kg pack
                'stock' => 75,
                'min_stock' => 15,
                'notes' => 'Beli sak 50kg, jual kemasan pack 1kg',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-019',
                'name' => 'Kopi Bubuk Robusta Dampit Murni 250gr',
                'category_id' => $catSembako->id,
                'buy_unit_id' => $unitPack->id,
                'sell_unit_id' => $unitPack->id,
                'conversion_factor' => 1,
                'last_purchase_price' => 22000,
                'avg_purchase_price' => 22000,
                'selling_price' => 30000,
                'stock' => 35,
                'min_stock' => 5,
                'notes' => 'Kopi bubuk khas lereng Semeru harum pekat',
                'is_active' => true,
            ],
        ];

        $products = [];
        foreach ($productsData as $pData) {
            $pData['business_id'] = $businessId;
            $products[] = Product::updateOrCreate(['sku' => $pData['sku']], $pData);
        }

        // ================= 7. PURCHASES (PEMBELIAN MASUK DARI SUPPLIER) =================
        // Purchase 1: 20 days ago (Paid)
        if (Purchase::where('invoice_number', 'PB-202608-001')->doesntExist()) {
            $p1Total = (20 * 150000) + (15 * 175000); // 3,000,000 + 2,625,000 = 5,625,000
            $pur1 = Purchase::create([
                'business_id' => $businessId,
                'invoice_number' => 'PB-202608-001',
                'supplier_id' => $suppliers[0]->id,
                'purchase_date' => Carbon::now()->subDays(20)->toDateString(),
                'total_amount' => $p1Total,
                'payment_status' => 'paid',
                'paid_amount' => $p1Total,
                'due_amount' => 0,
                'notes' => 'Stok awal pupuk bulanan',
                'created_by' => $admin->id,
            ]);

            PurchaseItem::create([
                'business_id' => $businessId,
                'purchase_id' => $pur1->id,
                'product_id' => $products[0]->id,
                'quantity' => 20,
                'unit_price' => 150000,
                'subtotal' => 3000000,
            ]);

            PurchaseItem::create([
                'business_id' => $businessId,
                'purchase_id' => $pur1->id,
                'product_id' => $products[1]->id,
                'quantity' => 15,
                'unit_price' => 175000,
                'subtotal' => 2625000,
            ]);
        }

        // Purchase 2: 14 days ago (Paid)
        if (Purchase::where('invoice_number', 'PB-202608-002')->doesntExist()) {
            $p2Total = (25 * 95000) + (20 * 70000); // 2,375,000 + 1,400,000 = 3,775,000
            $pur2 = Purchase::create([
                'business_id' => $businessId,
                'invoice_number' => 'PB-202608-002',
                'supplier_id' => $suppliers[1]->id,
                'purchase_date' => Carbon::now()->subDays(14)->toDateString(),
                'total_amount' => $p2Total,
                'payment_status' => 'paid',
                'paid_amount' => $p2Total,
                'due_amount' => 0,
                'notes' => 'Restock herbisida dan racun rumput',
                'created_by' => $admin->id,
            ]);

            PurchaseItem::create([
                'business_id' => $businessId,
                'purchase_id' => $pur2->id,
                'product_id' => $products[8]->id, // Roundup
                'quantity' => 25,
                'unit_price' => 95000,
                'subtotal' => 2375000,
            ]);

            PurchaseItem::create([
                'business_id' => $businessId,
                'purchase_id' => $pur2->id,
                'product_id' => $products[9]->id, // Gramoxone
                'quantity' => 20,
                'unit_price' => 70000,
                'subtotal' => 1400000,
            ]);
        }

        // Purchase 3: 7 days ago (Partial - Hutang Supplier)
        if (Purchase::where('invoice_number', 'PB-202608-003')->doesntExist()) {
            $p3Total = (8 * 420000) + (15 * 65000); // 3,360,000 + 975,000 = 4,335,000
            $pur3 = Purchase::create([
                'business_id' => $businessId,
                'invoice_number' => 'PB-202608-003',
                'supplier_id' => $suppliers[2]->id,
                'purchase_date' => Carbon::now()->subDays(7)->toDateString(),
                'total_amount' => $p3Total,
                'payment_status' => 'partial',
                'paid_amount' => 2335000,
                'due_amount' => 2000000, // sisa hutang
                'notes' => 'Pembelian sprayer elektrik & cangkul baja, tempo 30 hari',
                'created_by' => $admin->id,
            ]);

            PurchaseItem::create([
                'business_id' => $businessId,
                'purchase_id' => $pur3->id,
                'product_id' => $products[11]->id, // Sprayer DGW
                'quantity' => 8,
                'unit_price' => 420000,
                'subtotal' => 3360000,
            ]);

            PurchaseItem::create([
                'business_id' => $businessId,
                'purchase_id' => $pur3->id,
                'product_id' => $products[12]->id, // Cangkul Buaya
                'quantity' => 15,
                'unit_price' => 65000,
                'subtotal' => 975000,
            ]);

            // Supplier Payment: Pembayaran cicilan kemarin Rp 1.000.000
            SupplierPayment::create([
                'business_id' => $businessId,
                'purchase_id' => $pur3->id,
                'amount' => 1000000,
                'payment_date' => Carbon::now()->subDays(2)->toDateString(),
                'payment_method' => 'transfer',
                'notes' => 'Cicilan tahap 1 via transfer Bank BCA',
                'created_by' => $admin->id,
            ]);

            $pur3->update([
                'paid_amount' => 3335000,
                'due_amount' => 1000000, // Sisa hutang bersih Rp 1.000.000
            ]);
        }

        // Purchase 4: 2 days ago (Paid Sembako)
        if (Purchase::where('invoice_number', 'PB-202608-004')->doesntExist()) {
            $p4Total = (20 * 68000) + (10 * 90000); // 1,360,000 + 900,000 = 2,260,000
            $pur4 = Purchase::create([
                'business_id' => $businessId,
                'invoice_number' => 'PB-202608-004',
                'supplier_id' => $suppliers[4]->id,
                'purchase_date' => Carbon::now()->subDays(2)->toDateString(),
                'total_amount' => $p4Total,
                'payment_status' => 'paid',
                'paid_amount' => $p4Total,
                'due_amount' => 0,
                'notes' => 'Kulakan beras premium & minyak goreng',
                'created_by' => $admin->id,
            ]);

            PurchaseItem::create([
                'business_id' => $businessId,
                'purchase_id' => $pur4->id,
                'product_id' => $products[15]->id, // Beras Rojolele
                'quantity' => 20,
                'unit_price' => 68000,
                'subtotal' => 1360000,
            ]);

            PurchaseItem::create([
                'business_id' => $businessId,
                'purchase_id' => $pur4->id,
                'product_id' => $products[16]->id, // Minyak Goreng
                'quantity' => 10,
                'unit_price' => 90000,
                'subtotal' => 900000,
            ]);
        }

        // ================= 8. SALES (PENJUALAN KASIR POS REALISTIS) =================
        // Sale 1: 18 days ago (Cash - Paid)
        if (Sale::where('invoice_number', 'JL-202608-001')->doesntExist()) {
            $s1Total = (4 * 165000) + (2 * 105000); // 660,000 + 210,000 = 870,000
            $sale1 = Sale::create([
                'business_id' => $businessId,
                'invoice_number' => 'JL-202608-001',
                'customer_id' => $customers[0]->id,
                'sale_date' => Carbon::now()->subDays(18)->format('Y-m-d 09:30:00'),
                'total_amount' => $s1Total,
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'paid_amount' => $s1Total,
                'due_amount' => 0,
                'created_by' => $kasir->id,
            ]);

            SaleItem::create([
                'business_id' => $businessId,
                'sale_id' => $sale1->id,
                'product_id' => $products[0]->id,
                'quantity' => 4,
                'unit_price' => 165000,
                'subtotal' => 660000,
            ]);

            SaleItem::create([
                'business_id' => $businessId,
                'sale_id' => $sale1->id,
                'product_id' => $products[4]->id,
                'quantity' => 2,
                'unit_price' => 105000,
                'subtotal' => 210000,
            ]);
        }

        // Sale 2: 12 days ago (QRIS - Paid)
        if (Sale::where('invoice_number', 'JL-202608-002')->doesntExist()) {
            $s2Total = (1 * 520000) + (2 * 115000); // 520,000 + 230,000 = 750,000
            $sale2 = Sale::create([
                'business_id' => $businessId,
                'invoice_number' => 'JL-202608-002',
                'customer_id' => $customers[1]->id,
                'sale_date' => Carbon::now()->subDays(12)->format('Y-m-d 14:15:00'),
                'total_amount' => $s2Total,
                'payment_method' => 'qris',
                'payment_status' => 'paid',
                'paid_amount' => $s2Total,
                'due_amount' => 0,
                'created_by' => $kasir->id,
            ]);

            SaleItem::create([
                'business_id' => $businessId,
                'sale_id' => $sale2->id,
                'product_id' => $products[11]->id,
                'quantity' => 1,
                'unit_price' => 520000,
                'subtotal' => 520000,
            ]);

            SaleItem::create([
                'business_id' => $businessId,
                'sale_id' => $sale2->id,
                'product_id' => $products[8]->id,
                'quantity' => 2,
                'unit_price' => 115000,
                'subtotal' => 230000,
            ]);
        }

        // Sale 3: 8 days ago (Transfer Bank - Paid)
        if (Sale::where('invoice_number', 'JL-202608-003')->doesntExist()) {
            $s3Total = (6 * 195000) + (2 * 85000); // 1,170,000 + 170,000 = 1,340,000
            $sale3 = Sale::create([
                'business_id' => $businessId,
                'invoice_number' => 'JL-202608-003',
                'customer_id' => $customers[2]->id,
                'sale_date' => Carbon::now()->subDays(8)->format('Y-m-d 11:00:00'),
                'total_amount' => $s3Total,
                'payment_method' => 'transfer',
                'payment_status' => 'paid',
                'paid_amount' => $s3Total,
                'due_amount' => 0,
                'created_by' => $kasir->id,
            ]);

            SaleItem::create([
                'business_id' => $businessId,
                'sale_id' => $sale3->id,
                'product_id' => $products[1]->id,
                'quantity' => 6,
                'unit_price' => 195000,
                'subtotal' => 1170000,
            ]);

            SaleItem::create([
                'business_id' => $businessId,
                'sale_id' => $sale3->id,
                'product_id' => $products[12]->id,
                'quantity' => 2,
                'unit_price' => 85000,
                'subtotal' => 170000,
            ]);
        }

        // Sale 4: 5 days ago (Cash - Paid Sembako)
        if (Sale::where('invoice_number', 'JL-202608-004')->doesntExist()) {
            $s4Total = (2 * 78000) + (3 * 17500) + (2 * 30000); // 156,000 + 52,500 + 60,000 = 268,500
            $sale4 = Sale::create([
                'business_id' => $businessId,
                'invoice_number' => 'JL-202608-004',
                'customer_id' => $customers[4]->id,
                'sale_date' => Carbon::now()->subDays(5)->format('Y-m-d 16:40:00'),
                'total_amount' => $s4Total,
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'paid_amount' => $s4Total,
                'due_amount' => 0,
                'created_by' => $kasir->id,
            ]);

            SaleItem::create([
                'business_id' => $businessId,
                'sale_id' => $sale4->id,
                'product_id' => $products[15]->id,
                'quantity' => 2,
                'unit_price' => 78000,
                'subtotal' => 156000,
            ]);

            SaleItem::create([
                'business_id' => $businessId,
                'sale_id' => $sale4->id,
                'product_id' => $products[16]->id,
                'quantity' => 3,
                'unit_price' => 17500,
                'subtotal' => 52500,
            ]);

            SaleItem::create([
                'business_id' => $businessId,
                'sale_id' => $sale4->id,
                'product_id' => $products[18]->id,
                'quantity' => 2,
                'unit_price' => 30000,
                'subtotal' => 60000,
            ]);
        }

        // Sale 5: 3 days ago (Credit / Piutang Usaha Pelanggan)
        if (Sale::where('invoice_number', 'JL-202608-005')->doesntExist()) {
            $s5Total = (10 * 165000) + (5 * 80000); // 1,650,000 + 400,000 = 2,050,000
            $sale5 = Sale::create([
                'business_id' => $businessId,
                'invoice_number' => 'JL-202608-005',
                'customer_id' => $customers[3]->id, // Pak Haji Mahmud
                'sale_date' => Carbon::now()->subDays(3)->format('Y-m-d 10:20:00'),
                'total_amount' => $s5Total,
                'payment_method' => 'credit',
                'payment_status' => 'partial',
                'paid_amount' => 950000, // DP dibayar
                'due_amount' => 1100000, // sisa piutang
                'created_by' => $kasir->id,
            ]);

            SaleItem::create([
                'business_id' => $businessId,
                'sale_id' => $sale5->id,
                'product_id' => $products[0]->id,
                'quantity' => 10,
                'unit_price' => 165000,
                'subtotal' => 1650000,
            ]);

            SaleItem::create([
                'business_id' => $businessId,
                'sale_id' => $sale5->id,
                'product_id' => $products[5]->id,
                'quantity' => 5,
                'unit_price' => 80000,
                'subtotal' => 400000,
            ]);

            // Customer Payment: Cicilan piutang hari ini Rp 500.000 tunai
            CustomerPayment::create([
                'business_id' => $businessId,
                'sale_id' => $sale5->id,
                'amount' => 500000,
                'payment_date' => Carbon::now()->toDateString(),
                'payment_method' => 'cash',
                'notes' => 'Pembayaran cicilan piutang tunai oleh Pak Haji Mahmud',
                'created_by' => $kasir->id,
            ]);

            $sale5->update([
                'paid_amount' => 1450000,
                'due_amount' => 600000, // Sisa piutang bersih Rp 600.000
            ]);
        }

        // Sale 6: 2 days ago (QRIS - Paid)
        if (Sale::where('invoice_number', 'JL-202608-006')->doesntExist()) {
            $s6Total = (3 * 95000) + (2 * 45000); // 285,000 + 90,000 = 375,000
            $sale6 = Sale::create([
                'business_id' => $businessId,
                'invoice_number' => 'JL-202608-006',
                'customer_id' => $customers[1]->id,
                'sale_date' => Carbon::now()->subDays(2)->format('Y-m-d 13:10:00'),
                'total_amount' => $s6Total,
                'payment_method' => 'qris',
                'payment_status' => 'paid',
                'paid_amount' => $s6Total,
                'due_amount' => 0,
                'created_by' => $kasir->id,
            ]);

            SaleItem::create([
                'business_id' => $businessId,
                'sale_id' => $sale6->id,
                'product_id' => $products[3]->id,
                'quantity' => 3,
                'unit_price' => 95000,
                'subtotal' => 285000,
            ]);

            SaleItem::create([
                'business_id' => $businessId,
                'sale_id' => $sale6->id,
                'product_id' => $products[2]->id,
                'quantity' => 2,
                'unit_price' => 45000,
                'subtotal' => 90000,
            ]);
        }

        // Sale 7: Kemarin (Cash - Paid)
        if (Sale::where('invoice_number', 'JL-202608-007')->doesntExist()) {
            $s7Total = (2 * 38000) + (1 * 88000); // 76,000 + 88,000 = 164,000
            $sale7 = Sale::create([
                'business_id' => $businessId,
                'invoice_number' => 'JL-202608-007',
                'customer_id' => null, // Pelanggan Umum Walk-in
                'sale_date' => Carbon::now()->subDay()->format('Y-m-d 15:50:00'),
                'total_amount' => $s7Total,
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'paid_amount' => $s7Total,
                'due_amount' => 0,
                'created_by' => $kasir->id,
            ]);

            SaleItem::create([
                'business_id' => $businessId,
                'sale_id' => $sale7->id,
                'product_id' => $products[6]->id,
                'quantity' => 2,
                'unit_price' => 38000,
                'subtotal' => 76000,
            ]);

            SaleItem::create([
                'business_id' => $businessId,
                'sale_id' => $sale7->id,
                'product_id' => $products[9]->id,
                'quantity' => 1,
                'unit_price' => 88000,
                'subtotal' => 88000,
            ]);
        }

        // Sale 8: Hari ini (Cash - Paid)
        if (Sale::where('invoice_number', 'JL-202608-008')->doesntExist()) {
            $s8Total = (2 * 80000) + (2 * 165000); // 160,000 + 330,000 = 490,000
            $sale8 = Sale::create([
                'business_id' => $businessId,
                'invoice_number' => 'JL-202608-008',
                'customer_id' => $customers[0]->id,
                'sale_date' => Carbon::now()->format('Y-m-d 10:15:00'),
                'total_amount' => $s8Total,
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'paid_amount' => $s8Total,
                'due_amount' => 0,
                'created_by' => $kasir->id,
            ]);

            SaleItem::create([
                'business_id' => $businessId,
                'sale_id' => $sale8->id,
                'product_id' => $products[5]->id,
                'quantity' => 2,
                'unit_price' => 80000,
                'subtotal' => 160000,
            ]);

            SaleItem::create([
                'business_id' => $businessId,
                'sale_id' => $sale8->id,
                'product_id' => $products[0]->id,
                'quantity' => 2,
                'unit_price' => 165000,
                'subtotal' => 330000,
            ]);
        }

        // ================= 9. CASH TRANSACTIONS (BUKU KAS & ARUS KAS) =================
        $cashTransactionsData = [
            // Pemasukan
            [
                'type' => 'in',
                'amount' => 10000000,
                'account_id' => $bcaAccount?->id,
                'category' => 'Modal Tambahan Pemilik',
                'description' => 'Setoran modal tambahan kas awal usaha ke rekening Bank BCA',
                'transaction_date' => Carbon::now()->startOfMonth()->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'type' => 'in',
                'amount' => 1200000,
                'account_id' => $kasUtamaAccount?->id,
                'category' => 'Pendapatan Jasa Sewa Mesin',
                'description' => 'Penerimaan sewa mesin pompa air & kultivator 3 hari',
                'transaction_date' => Carbon::now()->subDays(10)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'type' => 'in',
                'amount' => 350000,
                'account_id' => $kasirAccount?->id,
                'category' => 'Pendapatan Jasa Pengiriman',
                'description' => 'Ongkos kirim pesanan pupuk ke kelompok tani',
                'transaction_date' => Carbon::now()->subDays(4)->toDateString(),
                'created_by' => $kasir->id,
            ],

            // Pengeluaran Beban Operasional
            [
                'type' => 'out',
                'amount' => 2500000,
                'account_id' => $bcaAccount?->id,
                'category' => 'Beban Sewa & Operasional Toko',
                'description' => 'Pembayaran sewa tempat usaha & ruko operasional bulanan',
                'transaction_date' => Carbon::now()->subDays(15)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'type' => 'out',
                'amount' => 450000,
                'account_id' => $kasUtamaAccount?->id,
                'category' => 'Beban Listrik, Air & Internet',
                'description' => 'Pembayaran tagihan listrik PLN dan air PDAM toko',
                'transaction_date' => Carbon::now()->subDays(11)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'type' => 'out',
                'amount' => 250000,
                'account_id' => $bcaAccount?->id,
                'category' => 'Beban Listrik, Air & Internet',
                'description' => 'Langganan WiFi internet toko & cloud backup',
                'transaction_date' => Carbon::now()->subDays(9)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'type' => 'out',
                'amount' => 200000,
                'account_id' => $kasUtamaAccount?->id,
                'category' => 'Beban Pemasaran & Promosi',
                'description' => 'Cetak banner spanduk promo musim tanam & brosur',
                'transaction_date' => Carbon::now()->subDays(6)->toDateString(),
                'created_by' => $admin->id,
            ],
            [
                'type' => 'out',
                'amount' => 120000,
                'account_id' => $kasirAccount?->id,
                'category' => 'Beban Lain-lain',
                'description' => 'Beli air galon minum & kopi untuk kasir dan tamu',
                'transaction_date' => Carbon::now()->subDays(3)->toDateString(),
                'created_by' => $kasir->id,
            ],
            [
                'type' => 'out',
                'amount' => 150000,
                'account_id' => $kasirAccount?->id,
                'category' => 'Beban Lain-lain',
                'description' => 'Pembelian kantong plastik belanja kresek & kertas struk thermal',
                'transaction_date' => Carbon::now()->subDay()->toDateString(),
                'created_by' => $kasir->id,
            ],
        ];

        foreach ($cashTransactionsData as $cTx) {
            $cTx['business_id'] = $businessId;
            CashTransaction::firstOrCreate(
                [
                    'business_id' => $businessId,
                    'category' => $cTx['category'],
                    'amount' => $cTx['amount'],
                    'transaction_date' => $cTx['transaction_date'],
                ],
                $cTx
            );
        }

        // ================= 10. CASH REGISTER (SHIFT KASIR) =================
        // Yesterday's closed shift
        CashRegister::firstOrCreate(
            [
                'business_id' => $businessId,
                'user_id' => $kasir->id,
                'opened_at' => Carbon::now()->subDay()->format('Y-m-d 08:00:00'),
            ],
            [
                'business_id' => $businessId,
                'closed_at' => Carbon::now()->subDay()->format('Y-m-d 17:00:00'),
                'initial_cash' => 300000,
                'total_cash_sales' => 164000,
                'total_non_cash_sales' => 0,
                'total_cash_in' => 0,
                'total_cash_out' => 0,
                'expected_cash' => 464000,
                'actual_cash' => 464000,
                'difference' => 0,
                'status' => 'closed',
                'notes' => 'Shift kasir kemarin selesai, uang fisik di laci cocok',
            ]
        );

        // Today's open shift for Siti Kasir (so POS is ready for cashier)
        CashRegister::firstOrCreate(
            [
                'business_id' => $businessId,
                'user_id' => $kasir->id,
                'opened_at' => Carbon::now()->format('Y-m-d 08:00:00'),
                'status' => 'open',
            ],
            [
                'business_id' => $businessId,
                'initial_cash' => 300000,
                'total_cash_sales' => 490000,
                'total_non_cash_sales' => 0,
                'total_cash_in' => 500000, // cicilan piutang masuk kasir
                'total_cash_out' => 0,
                'expected_cash' => 1290000,
                'status' => 'open',
                'notes' => 'Shift pagi kasir sedang berlangsung',
            ]
        );
    }
}
