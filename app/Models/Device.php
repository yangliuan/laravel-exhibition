<?php

namespace App\Models;

use App\Traits\DateFormat;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Device extends Authenticatable
{
    use HasApiTokens, DateFormat;

    protected $table = 'devices';

    protected $fillable = ['mac_address', 'alias'];

    protected $dates = [];

    protected $casts = [];

    protected $appends = [];

    protected $hidden = [
        'pivot',
    ];

    public function asset()
    {
        return $this->belongsToMany(Asset::class, 'device_asset', 'dev_id', 'asset_id')
            ->withTimestamps()
            ->using(DeviceAsset::class);
    }
}
