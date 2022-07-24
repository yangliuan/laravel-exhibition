<?php

namespace App\Models;

class DeviceAsset extends BasePivot
{
    protected $table = 'device_assets';

    protected $fillable = ['dev_id', 'asset_id', 'sort'];

    protected $dates = [];

    protected $casts = [];

    protected $appends = [];

    protected $hidden = [
        'pivot'
    ];
}
