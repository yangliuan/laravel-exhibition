<?php

namespace App\Models;

class Asset extends BaseModel
{   
    protected $table = 'assets';

    protected $fillable = ['asset_type', 'path'];

    protected $dates = [];

    protected $casts = [];

    protected $appends = [];

    protected $hidden = [
        'pivot'
    ];
}
