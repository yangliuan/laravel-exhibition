<?php
/**
 * 全局删除事件
 */

namespace App\Events;

use App\Models\BaseModel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BaseModelDeleted
{
    use Dispatchable, SerializesModels;

    protected $base_model;

    public function __construct(BaseModel $base_model)
    {
        $this->base_model = $base_model;
    }

    public function getBaseModel()
    {
        return $this->base_model;
    }
}
