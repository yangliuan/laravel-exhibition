<?php

namespace App\Models;

class ExcelDemo extends BaseModel
{   
    protected $table = 'excel_demos';

    protected $fillable = ['str_column', 'int_column', 'float_column', 'pic_column', 'text_column'];

    protected $dates = [];

    protected $casts = [];

    protected $appends = [];

    protected $hidden = [
        'pivot'
    ];
}
