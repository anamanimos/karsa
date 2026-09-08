<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $business = \App\Models\Business::first();
        if ($business) {
            session(['active_business_id' => $business->id]);
        }

        $units = [
            ['name' => 'Pieces', 'symbol' => 'pcs'],
            ['name' => 'Box', 'symbol' => 'box'],
            ['name' => 'Pack', 'symbol' => 'pack'],
            ['name' => 'Kilogram', 'symbol' => 'kg'],
            ['name' => 'Gram', 'symbol' => 'gr'],
            ['name' => 'Liter', 'symbol' => 'liter'],
            ['name' => 'Meter', 'symbol' => 'm'],
            ['name' => 'Karung / Sak', 'symbol' => 'sak'],
            ['name' => 'Lusin', 'symbol' => 'lsn'],
            ['name' => 'Botol', 'symbol' => 'btl'],
            ['name' => 'Jam / Sesi', 'symbol' => 'jam'],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(['symbol' => $unit['symbol']], $unit);
        }
    }
}
