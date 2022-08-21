<?php

namespace App\Models;

class UserThirdPartyLogin extends BaseModel
{   
    protected $table = 'user_third_party_logins';

    protected $fillable = ['user_id', 'thirdparty', 'identifier', 'tnickname', 'tavatar'];

    protected $dates = [];

    protected $casts = [];

    protected $appends = [];

    protected $hidden = [
        'pivot'
    ];
}
