<?php

namespace App\Http\Controllers\Api;

use App\Events\AllDeviceEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ControlController extends Controller
{
    public function sendInstruction(Request $request)
    {
        $res = AllDeviceEvent::dispatch([
            'action_type' => 'move',
            'action' => 'right',
        ]);
        dump($res);
    }
}
