<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Group extends Model
{
    protected static $unguarded = true;

    public function scenes()
    {
        return $this->hasMany(Scene::class);
    }

    public function addBulbs(Collection $bulbs)
    {
        $bulbs->each(function(Bulb $bulb) {
            $this->addBulb($bulb);
        });
    }

    public function bulbs()
    {
        return $this->groupedBulbs->map->bulb;
    }

    public function groupedBulbs()
    {
        return $this->hasMany(GroupedBulb::class);
    }

    private function addBulb($bulb)
    {
        $this->groupedBulbs()->create([
            'bulb_id' => $bulb->id
        ]);
    }
}
