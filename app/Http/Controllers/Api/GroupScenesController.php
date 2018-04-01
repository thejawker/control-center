<?php

namespace App\Http\Controllers\Api;

use App\Group;
use App\GroupedBulb;
use App\Http\Controllers\Controller;

class GroupScenesController extends Controller
{
    public function index($id)
    {
        return response()->json(Group::findOrFail($id)->scenes);
    }

    public function store($id)
    {
        $scene = Group::findOrFail($id)->scenes()->create(request()->validate([
            'name' => ''
        ]));

        $scene->makeSnapshot();

        return response()->json(null, 201);
    }

    public function update($groupId, $sceneId)
    {
        $scene = Group::findOrFail($groupId)->scenes()->findOrFail($sceneId);

        $scene->makeSnapshot();

        return response()->json(null, 200);
    }
}
