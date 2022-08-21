<?php

namespace App\Models;

class ExcelDemoPicture extends BaseModel
{
    protected $table = 'excel_demo_pictures';

    protected $fillable = ['str_column', 'int_column', 'float_column', 'pic_column', 'text_column'];

    protected $dates = [];

    protected $casts = [];

    protected $appends = [];

    protected $hidden = [
        'pivot',
    ];
}
