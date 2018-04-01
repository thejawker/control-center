<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BulbSetting extends Model
{
    protected static $unguarded = true;

    protected $casts = [
        'powered' => 'bool'
    ];
}
