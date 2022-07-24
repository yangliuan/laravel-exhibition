<?php

namespace App\Models;

class ControlInstruction extends BaseModel
{   
    protected $table = 'control_instructions';

    protected $fillable = ['dev_id', 'action_type', 'action'];

    protected $dates = [];

    protected $casts = [];

    protected $appends = [];

    protected $hidden = [
        'pivot'
    ];
}
