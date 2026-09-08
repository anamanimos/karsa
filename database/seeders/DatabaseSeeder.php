<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            PlanSeeder::class,
            BusinessSettingSeeder::class,
            CategorySeeder::class,
            UnitSeeder::class,
            AccountSeeder::class,
            EmployeeSeeder::class,
            DummyDataSeeder::class,
        ]);
    }
}
