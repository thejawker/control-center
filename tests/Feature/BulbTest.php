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
        $this->withoutExceptionHandling();
        $createdBulb = factory(Bulb::class)->create([
            'device_id' => 'something-weird',
            'model' => 'some-model',
            'ip' => '192.10.10.10'
        ]);

        $response = $this->putJson("/api/bulbs/{$createdBulb->id}", [
            'name' => 'New Name'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'device_id' => 'something-weird',
            'model' => 'some-model',
            'ip' => '192.10.10.10',
            'name' => 'New Name'
        ]);
    }

    /** @test */
    public function name_must_be_more_than_3_chars()
    {
        $createdBulb = factory(Bulb::class)->create([
            'device_id' => 'something-weird',
            'model' => 'some-model',
            'ip' => '192.10.10.10'
        ]);

        $response = $this->putJson("/api/bulbs/{$createdBulb->id}", [
            'name' => '123'
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function name_cant_be_more_than_64_chars()
    {
        $createdBulb = factory(Bulb::class)->create([
            'device_id' => 'something-weird',
            'model' => 'some-model',
            'ip' => '192.10.10.10'
        ]);

        $response = $this->putJson("/api/bulbs/{$createdBulb->id}", [
            'name' => str_repeat('1', 65)
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function a_bulb_can_be_updated_to_a_color()
    {
        $createdBulb = factory(Bulb::class)->create([
            'device_id' => 'something-weird',
            'model' => 'some-model',
            'ip' => '192.10.10.10',
            'name' => 'Some name'
        ]);

        $response = $this->putJson("/api/bulbs/{$createdBulb->id}", [
            'color' => 'rgbw(101,102,103,104)',
            'powered' => true
        ]);

        $response->assertStatus(200);
    }
    
    /** @test */
    public function bulbs_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $bulbs = factory(Bulb::class, 23)->create();

        $response = $this->putJson("/api/bulbs", [
            'color' => 'rgbw(101,102,103,104)',
            'powered' => true
        ]);

        $response->assertStatus(200);
    }
}