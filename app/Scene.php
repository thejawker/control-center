<?php

namespace App;

use Facades\App\BulbSettingExtractor;
use Illuminate\Database\Eloquent\Model;

class Scene extends Model
{
    protected static $unguarded = true;

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function makeSnapshot()
    {
        $this->group->bulbs()->each(function (Bulb $bulb) {
            $this->makeSettingFromBulb($bulb);
        });

        return $this;
    }

    private function makeSettingFromBulb(Bulb $bulb)
    {
        $this->bulbSettings()->updateOrCreate([
            'bulb_id' => $bulb->id
        ], BulbSettingExtractor::fromBulb($bulb));
    }

    public function bulbSettings()
    {
        return $this->hasMany(BulbSetting::class);
    }
}
