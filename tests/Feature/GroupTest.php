<?php

namespace Tests\Feature;

use App\Bulb;
use App\Group;
use App\GroupedBulb;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_list_of_groups_with_their_bulbs_can_be_retrieved()
    {
        $groups = factory(Group::class, 28)->create();
        $groups->each(function (Group $group) {
            $bulbs = factory(Bulb::class, 5)->create();
            $group->addBulbs($bulbs);
        });

        $response = $this->getJson('/api/groups');

        $response->assertStatus(200);
        $response->assertJsonCount(28);
        $response->assertJson([
            [
                'grouped_bulbs' => [
                    [
                        'bulb' => [
                            'name' => null
                        ]
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function a_specific_group_can_be_1retrieved()
    {
        $group = factory(Group::class)->create();
        $group->addBulbs(
            factory(Bulb::class, 4)->create()
        );

        $response = $this->getJson("/api/groups/{$group->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'grouped_bulbs' => [
                [
                    'bulb' => [
                        'name' => null
                    ]
                ]
            ]
        ]);
    }
    
    /** @test */
    public function a_group_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $group = factory(Group::class)->create([]);
        $this->assertNull($group->name);

        $response = $this->putJson("/api/groups/{$group->id}", [
            'name' => 'Living Room'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'Living Room'
        ]);
    }

    /** @test */
    public function name_must_be_more_than_3_chars()
    {
        $group = factory(Group::class)->create([]);
        $this->assertNull($group->name);

        $response = $this->putJson("/api/groups/{$group->id}", [
            'name' => '123'
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function name_cant_be_more_than_65_chars()
    {
        $group = factory(Group::class)->create([]);
        $this->assertNull($group->name);

        $response = $this->putJson("/api/groups/{$group->id}", [
            'name' => str_repeat('1', 65)
        ]);

        $response->assertStatus(422);
    }
}