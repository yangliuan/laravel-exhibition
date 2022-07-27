<?php

namespace App\Broadcasting;

use App\Models\Device;
use App\Models\User;

class DeviceChannel
{
    public $device_id;

    public function __construct($id)
    {
        $this->device_id = $id;
    }

    public function join()
    {
        $device = Device::find($this->device_id);

        if ($device instanceof Device) {
            return true;
        }

        return false;
    }
}
