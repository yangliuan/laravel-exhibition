<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuthDeviceEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $control_instruction;

    public $dev_id;

    public function __construct(array $control_instruction, int $dev_id)
    {
        $this->control_instruction = $control_instruction;
        $this->dev_id = $dev_id;
    }

    /**
     * 广播频道名称
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('device.'.$this->dev_id);
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
