<?php

namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceTableSeeder extends Seeder
{
    public function run()
    {
        $at = now();

        $data = [
            [
                'mac_address' => 'FC:34:97:97:AA:45',
                'alias' => '固定测试设备',
                'created_at' => $at,
                'updated_at' => $at,
            ],
        ];

        $faker = \Faker\Factory::create('zh_CN');

        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'mac_address' => $faker->macAddress,
                'alias' => '测试设备'.$faker->randomNumber(5, true),
                'created_at' => $at,
                'updated_at' => $at,
            ];
        }

        DB::table('devices')->insert($data);
    }
}
