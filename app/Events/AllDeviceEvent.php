<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AllDeviceEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $control_instruction;

    public function __construct(array $control_instruction)
    {
        $this->control_instruction = $control_instruction;
    }

    public function broadcastOn()
    {
        return new Channel('all-device');
    }

    //定义广播数据
    public function broadcastWith()
    {
        return [
            'action_type' => $this->control_instruction['action_type'],
            'action' => $this->control_instruction['action']
        ];
    }
}
