<?php

namespace App\Http\Controllers\Api;

use App\GroupedBulb;
use App\Http\Controllers\Controller;

class GroupedBulbsController extends Controller
{
    public function store()
    {
        $groupedBulb = GroupedBulb::updateOrCreate(request()->validate([
            'group_id' => 'exists:groups,id',
            'bulb_id' => 'exists:bulbs,id',
        ]));
        return response()->json($groupedBulb, 201);
    }

    public function destroy($id)
    {
        GroupedBulb::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
