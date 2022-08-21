<?php

namespace App\Models;

use App\Casts\FileUrl;
use App\Casts\FileUrlJson;

class ImageDemo extends BaseModel
{
    protected $table = 'image_demos';

    protected $fillable = ['path','path_group'];

    protected $dates = [];

    protected $casts = [
        'path' => FileUrl::class,
        'path_group' => FileUrlJson::class
    ];

    protected $appends = [];

    protected $hidden = [
        'pivot'
    ];
}
