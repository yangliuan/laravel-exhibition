<?php

namespace App\Http\Controllers\Api;

use App\Events\AllDeviceEvent;
use App\Events\AuthDeviceEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ControlController extends Controller
{
    public function allDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action_type' => 'bail|required|string|in:move',
            'action' => 'bail|required|string|in:prev,next'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422, 'msg' => $validator->errors()->first(),
            ]);
        }

        AllDeviceEvent::dispatch($request->only(['action_type','action']));

        return response()->json([
            'code' => 200, 'msg' => 'success'
        ]);
    }

    public function device(Request $request, $devId)
    {
        $validator = Validator::make($request->all(), [
            'action_type' => 'bail|required|string|in:move',
            'action' => 'bail|required|string|in:prev,next'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422, 'msg' => $validator->errors()->first(),
            ]);
        }

        AuthDeviceEvent::dispatch($request->only(['action_type','action']), $devId);

        return response()->json([
            'code' => 200, 'msg' => 'success'
        ]);
    }
}
