<?php

namespace App\Http\Controllers\Lbs;

use App\Http\Controllers\Controller;
use GeoIp2\Database\Reader;
use Illuminate\Http\Request;

class GeoIpController extends Controller
{
    public function demo(Request $request)
    {
        $reader = new Reader(\storage_path('app/geoip/GeoLite2-City.mmdb'));
        $record = $reader->city('128.101.101.101');
        echo $record->country->isoCode."\n"; // 'US'
        echo $record->country->name."\n"; // 'United States'
        echo $record->country->names['zh-CN']."\n"; // '美国'

        echo $record->mostSpecificSubdivision->name."\n"; // 'Minnesota'
        echo $record->mostSpecificSubdivision->isoCode."\n"; // 'MN'

        echo $record->city->name;
        echo $record->city->names['zh-CN']."\n"; // 'Minneapolis'

        echo $record->postal->code."\n"; // '55455'

        echo $record->location->latitude."\n"; // 44.9733
        echo $record->location->longitude."\n"; // -93.2323

        echo $record->traits->network."\n"; // '128.101.101.101/32'
    }
}
