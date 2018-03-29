<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bulb extends Model
{
    protected static $unguarded = true;

    public static function storeHardwareBulbs(array $hardwareBulbs)
    {
        collect($hardwareBulbs)->each(function ($bulb) {
            self::updateOrCreate([
                'device_id' => $bulb['id'],
            ], [
                'ip' => $bulb['ip'],
                'model' => $bulb['model']
            ]);
        });
    }

    public static function fromDeviceId($id)
    {
        return self::whereDeviceId($id)->first();
    }
}
