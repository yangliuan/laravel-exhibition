<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetTableSeeder extends Seeder
{
    public function run()
    {
        $url = config('app.url');
        $data = [
            [
                'asset_type' => 'img',
                'path' => $url.'/show/seeder.jpeg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'asset_type' => 'audio',
                'path' => $url.'/show/seeder.mp3',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'asset_type' => 'video',
                'path' => $url.'/show/seeder.mp4',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        DB::table('assets')->insert($data);
    }
}
