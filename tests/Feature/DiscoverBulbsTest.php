<?php

namespace Tests\Feature;

use App\Bulb;
use Tests\TestCase;
use Facades\App\BulbScanner;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DiscoverBulbsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        BulbScanner::swap(new class
        {
            public function discover()
            {
                return [
                    "light-id-1" => [
                        "ip" => "192.168.1.1",
                        "id" => "light-id-1",
                        "model" => "model-a"
                    ],
                    "light-id-2" => [
                        "ip" => "192.168.1.2",
                        "id" => "light-id-2",
                        "model" => "model-b"
                    ],
                    "light-id-3" => [
                        "ip" => "192.168.1.3",
                        "id" => "light-id-3",
                        "model" => "model-a"
                    ],
                ];
            }
        });
    }

    /** @test */
    public function lights_can_be_discovered()
    {
        $response = $this->post('/api/discover');

        $response->assertStatus(201);
        $this->assertCount(3, Bulb::all());
    }
    
    /** @test */
    public function lights_contain_specified_information()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/discover');

        $response->assertStatus(201);

        $bulb = Bulb::first();
        $this->assertEquals('192.168.1.1', $bulb->ip);
        $this->assertEquals('light-id-1', $bulb->device_id);
        $this->assertEquals('model-a', $bulb->model);
    }
    
    /** @test */
    public function bulbs_with_the_same_device_id_are_updated_instead_of_added()
    {
        $existingBulb = factory(Bulb::class)->create([
            'device_id' => 'light-id-2',
            'ip' => '111.1.1.11',
            'model' => 'other-model'
        ]);

        // Will update the Bulb.
        $response = $this->post('/api/discover');

        $response->assertStatus(201);
        $this->assertCount(3, Bulb::all());
        $bulb = Bulb::fromDeviceId('light-id-2');
        $this->assertEquals('192.168.1.2', $bulb->ip);
        $this->assertEquals('model-b', $bulb->model);
    }
}