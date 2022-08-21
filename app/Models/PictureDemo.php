<?php

namespace App\Models;

use App\Casts\FileUrlByDisk;

class PictureDemo extends BaseModel
{
    protected $table = 'picture_demos';

    protected $fillable = ['disk', 'path'];

    protected $dates = [];

    protected $casts = [
        'path' => FileUrlByDisk::class
    ];

    protected $appends = [];

    protected $hidden = [
        'pivot'
    ];
}
