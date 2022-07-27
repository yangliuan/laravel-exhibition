<?php

use App\Broadcasting\DeviceChannel;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('all-device', function () {
    return true;
});

Broadcast::channel('device.{id}', DeviceChannel::class, [ 'guards' => ['device']]);
