<?php

namespace App\Models;

use App\Traits\DateFormat;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

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

    public function getToken()
    {
        return $this->createToken('device', ['display'])->plainTextToken;
    }
}
