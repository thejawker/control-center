<?php

namespace Tests\Feature;

use App\Bulb;
use Facades\App\BulbSettingExtractor;
use App\Group;
use App\Scene;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SceneTest extends TestCase
{
    use RefreshDatabase;

    public function setup()
    {
        parent::setUp();

        BulbSettingExtractor::swap(new class {
            public function fromBulb($bulb)
            {
                return [
                    'powered' => true,
                    'color' => 'rgbw(255,255,255,255)',
                ];
            }
        });
    }
    
    /** @test */
    public function a_list_of_scenes_per_group_can_be_shown()
    {
        $this->withoutExceptionHandling();

        $bulbs = factory(Bulb::class, 4)->create();
        $group = factory(Group::class)->create();
        $group->addBulbs($bulbs);
        $scene = factory(Scene::class)->create([
            'group_id' => $group->id
        ])->makeSnapshot();
        $scene = factory(Scene::class)->create([
            'group_id' => $group->id
        ])->makeSnapshot();

        $response = $this->getJson("/api/groups/{$group->id}/scenes");

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    /** @test */
    public function a_scene_can_be_made_based_on_a_group_with_bulbs()
    {
        $this->withoutExceptionHandling();

        $bulbs = factory(Bulb::class, 4)->create();
        $group = factory(Group::class)->create();
        $group->addBulbs($bulbs);

        $response = $this->postJson("/api/groups/{$group->id}/scenes", [
            'group_id' => $group->id,
            'name' => 'Scene Number 1'
        ]);

        $response->assertStatus(201);
        $scene = Scene::first();
        $this->assertNotNull($scene);
        $this->assertEquals('Scene Number 1', $scene->name);
        $this->assertTrue($group->is($scene->group));
    }

    /** @test */
    public function scene_bulb_settings_are_stored_for_the_bulbs_inside_the_group()
    {
        $this->withoutExceptionHandling();

        $bulbs = factory(Bulb::class, 4)->create();
        $group = factory(Group::class)->create();
        $group->addBulbs($bulbs);

        $response = $this->postJson("/api/groups/{$group->id}/scenes", [
            'group_id' => $group->id,
            'name' => 'Scene Number 1'
        ]);

        $response->assertStatus(201);
        $scene = Scene::first();
        $this->assertCount(4, $scene->bulbSettings);
        $bulbSetting = $scene->bulbSettings()->first();
        $this->assertEquals(true, $bulbSetting->powered);
        $this->assertEquals('rgbw(255,255,255,255)', $bulbSetting->color);
    }
    
    /** @test */
    public function a_scene_can_be_updated_by_simply_calling_the_method()
    {
        $this->withoutExceptionHandling();

        $bulbs = factory(Bulb::class, 4)->create();
        $group = factory(Group::class)->create();
        $group->addBulbs($bulbs);
        $scene = factory(Scene::class)->create([
            'group_id' => $group->id
        ])->makeSnapshot();

        BulbSettingExtractor::swap(new class {
            public function fromBulb($bulb)
            {
                return [
                    'powered' => false,
                    'color' => 'rgbw(32,23,22,22)',
                ];
            }
        });

        $response = $this->putJson("/api/groups/{$group->id}/scenes/{$scene->id}", [

        ]);

        $response->assertStatus(200);
        $bulbSetting = $scene->bulbSettings()->first();
        $this->assertEquals(false, $bulbSetting->powered);
        $this->assertEquals('rgbw(32,23,22,22)', $bulbSetting->color);
    }
}