<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupedBulb extends Model
{
    protected static $unguarded = true;

    public function bulb()
    {
        return $this->belongsTo(Bulb::class);
    }
}
