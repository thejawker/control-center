<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use TheJawker\ControlStuff\LedFlux\Bulb\Bulb as HardwareBulb;
use TheJawker\ControlStuff\LedFlux\ColorSetting;

class Bulb extends Model
{
    protected static $unguarded = true;
    /** @var HardwareBulb */
    private $hardwareBulb;

    public function updateSettings(array $settings)
    {
        $this->hardwareBulb()->setColor(
            ColorSetting::fromString(Arr::get($settings, 'color'))
        );

        $this->hardwareBulb()->powered(Arr::get($settings, 'powered'));
    }

    public static function storeHardwareBulbs(array $hardwareBulbs)
    {
        return collect($hardwareBulbs)->map(function ($bulb) {
            [$ip, $id, $model] = $bulb;

            return self::updateOrCreate([
                'device_id' => $id,
            ], [
                'ip' => $ip,
                'model' => $model
            ]);
        });
    }

    public static function fromDeviceId($id)
    {
        return self::whereDeviceId($id)->first();
    }

    public function hardwareBulb()
    {
        if (!$this->hardwareBulb) {
            $this->hardwareBulb = new HardwareBulb($this->ip);
        }

        return $this->hardwareBulb;
    }
}
