<?php

namespace Tests\Feature;

use App\Bulb;
use App\Group;
use App\GroupedBulb;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupedBulbTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bulb_can_be_added_to_the_group()
    {
        $group = factory(Group::class)->create();
        $bulb = factory(Bulb::class)->create();

        $response = $this->postJson('/api/grouped-bulbs', [
            'group_id' => $group->id,
            'bulb_id' => $bulb->id
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'group_id' => $group->id,
            'bulb_id' => $bulb->id
        ]);
    }

    /** @test */
    public function the_bulb_id_must_exist()
    {
        $group = factory(Group::class)->create();

        $response = $this->postJson('/api/grouped-bulbs', [
            'group_id' => $group->id,
            'bulb_id' => 123
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function the_group_id_must_exist()
    {
        $bulb = factory(Bulb::class)->create();


        $response = $this->postJson('/api/grouped-bulbs', [
            'group_id' => 123,
            'bulb_id' => $bulb->id
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function the_grouped_bulb_cant_be_created_twice()
    {
        $group = factory(Group::class)->create();
        $bulb = factory(Bulb::class)->create();
        GroupedBulb::create([
            'group_id' => $group->id,
            'bulb_id' => $bulb->id
        ]);

        $response = $this->postJson('/api/grouped-bulbs', [
            'group_id' => $group->id,
            'bulb_id' => $bulb->id
        ]);

        $this->assertCount(1, GroupedBulb::whereGroupId($group->id)->whereBulbId($bulb->id)->get());
    }

    /** @test */
    public function a_grouped_bulb_can_be_deleted()
    {
        $this->withoutExceptionHandling();
        $groupedBulb = factory(GroupedBulb::class)->create();
        $this->assertNotNull($groupedBulb);

        $response = $this->deleteJson("/api/grouped-bulbs/{$groupedBulb->id}");

        $response->assertStatus(204);
        $this->assertNull($groupedBulb->fresh());
    }

    /** @test */
    public function a_non_existing_bulb_cannot_be_removed()
    {
        $groupedBulb = factory(GroupedBulb::class)->create();
        $this->assertNotNull($groupedBulb);

        $response = $this->deleteJson("/api/grouped-bulbs/1234");

        $response->assertStatus(404);
    }
}