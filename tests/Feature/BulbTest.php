<?php

namespace Tests\Feature;

use App\Bulb;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BulbTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_list_of_bulbs_can_be_returned()
    {
        factory(Bulb::class, 15)->create();

        $response = $this->getJson('/api/bulbs');

        $response->assertStatus(200);
        $response->assertJsonCount(15);
    }

    /** @test */
    public function a_specific_bulb_can_be_retrieved()
    {
        factory(Bulb::class, 15)->create();
        $specificBulb = factory(Bulb::class)->create([
            'device_id' => 'something-weird',
            'model' => 'some-model',
            'ip' => '192.10.10.10'
        ]);

        $response = $this->getJson("/api/bulbs/{$specificBulb->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $specificBulb->id,
            'device_id' => 'something-weird',
            'model' => 'some-model',
            'ip' => '192.10.10.10'
        ]);
    }

    /** @test */
    public function a_bulb_can_be_updated()
    {
        $createdBulb = factory(Bulb::class)->create([
            'device_id' => 'something-weird',
            'model' => 'some-model',
            'ip' => '192.10.10.10'
        ]);

        $response = $this->getJson("/api/bulbs/{$createdBulb->id}");
    }
}