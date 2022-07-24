<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asset;
use Illuminate\Support\Facades\DB;

class AssetTableSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'asset_type' => 'img',
                'path' => 'http://api.exhibition.demo/show/seeder.jpeg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'asset_type' => 'audio',
                'path' => 'http://api.exhibition.demo/show/seeder.mp3',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'asset_type' => 'video',
                'path' => 'http://api.exhibition.demo/show/seeder.mp4',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        DB::table('assets')->insert($data);
    }
}
