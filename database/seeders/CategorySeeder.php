<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $business = \App\Models\Business::first();
        if ($business) {
            session(['active_business_id' => $business->id]);
        }

        $categories = [
            ['name' => 'Makanan & Minuman', 'description' => 'Produk makanan, camilan, dan minuman kemasan atau segar'],
            ['name' => 'Kebutuhan Harian & Sembako', 'description' => 'Beras, minyak, gula, sabun, dan kebutuhan rumah tangga'],
            ['name' => 'Elektronik & Aksesoris', 'description' => 'Aksesoris gadget, perkabelan, dan perkakas elektronik'],
            ['name' => 'Pakaian & Fashion', 'description' => 'Baju, celana, alas kaki, dan aksesoris sandang'],
            ['name' => 'Pertanian & Bahan Baku', 'description' => 'Pupuk, benih, pakan, bibit, dan perlengkapan tani'],
            ['name' => 'Jasa & Layanan', 'description' => 'Layanan jasa perbaikan, konsultasi, atau sewa'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
