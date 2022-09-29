<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DisplayController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mac' => 'bail|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422, 'msg' => $validator->errors()->first(),
            ]);
        }

        $device = Device::where('mac_address', $request->input('mac'))->first();

        if ($device instanceof Device) {
            $token = $device->getToken();

            return response()->json([
                'code' => 200, 'msg' => 'success', 'token' => $token,
            ]);
        }

        return response()->json([
            'code' => 422, 'msg' => '设备不存在',
        ]);
    }

    public function asset(Request $request)
    {
        $assets = $request->user('device')
            ->asset()
            ->select('assets.id', 'asset_type', 'path')
            ->orderBy('device_asset.sort', 'desc')
            ->get();

        return response()->json([
            'code' => 200, 'msg' => 'success', 'assets' => $assets,
        ]);
    }
}
