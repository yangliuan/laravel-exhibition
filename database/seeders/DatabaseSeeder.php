<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AssetTableSeeder::class,
            DeviceTableSeeder::class,
            DeviceAssetTableSeeder::class,
            ExcelDemoTableSeeder::class,
            ExcelDemoPictureSeeder::class,
        ]);
    }
}
