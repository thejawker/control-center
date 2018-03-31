<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use TheJawker\ControlStuff\LedFlux\ColorSetting;
use TheJawker\ControlStuff\LedFlux\Bulb\Bulb as HardwareBulb;

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
        collect($hardwareBulbs)->each(function ($bulb) {
            $bulb = [
                'ip' => $bulb[0],
                'id' => $bulb[1],
                'model' => $bulb[2]
            ];
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

    private function hardwareBulb()
    {
        if (!$this->hardwareBulb) {
            $this->hardwareBulb = new HardwareBulb($this->ip);
        }

        return $this->hardwareBulb;
    }
}
