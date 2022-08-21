<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExcelDemo;

class ExcelDemoTableSeeder extends Seeder
{
    public function run()
    {
        ExcelDemo::factory()->count(10000)->create();
    }
}
