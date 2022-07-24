<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Device;
use Illuminate\Database\Seeder;
use App\Models\DeviceAsset;
use Illuminate\Support\Facades\DB;

class DeviceAssetTableSeeder extends Seeder
{
    public function run()
    {
        $device_ids = Device::pluck('id')->toArray();
        $asset_ids = Asset::pluck('id')->toArray();
        $i = 0;

        foreach ($device_ids as $key => $device_id) {
            foreach ($asset_ids as $key => $asset_id) {
                $data[] = [
                    'dev_id' => $device_id,
                    'asset_id' => $asset_id,
                    'sort' => $i,
                    'created_at' => date('Y-m-d H:i:s') ,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $i++;
            }
        }

        DB::table('device_asset')->insert($data);

    }
}

