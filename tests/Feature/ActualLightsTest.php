<?php

namespace Tests\Feature;

use App\Bulb;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use TheJawker\ControlStuff\LedFlux\ColorSetting;

class ActualLightsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function things()
    {
        $this->post('/api/discover');

        Bulb::get()->each(function(Bulb $bulb) {
//            $bulb->hardwareBulb()->toggle();
            $bulb->hardwareBulb()->setColor(ColorSetting::fromString('rgbw(255,5,5,20)'));
        });

        $this->fail('');
    }
}