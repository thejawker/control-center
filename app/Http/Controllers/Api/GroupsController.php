<?php

namespace App\Http\Controllers\Api;

use App\Group;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class GroupsController extends Controller
{
    public function index()
    {
        return $this->groups()->get();
    }

    public function show($id)
    {
        return $this->groups()->findOrFail($id);
    }

    public function update($id)
    {
        $group = $this->groups()->findOrFail($id);

        $group->update(request()->validate([
            'name' => 'between:4,64'
        ]));

        return response()->json($group);
    }

    /**
     * @return Builder|static
     */
    private function groups()
    {
        return Group::with('groupedBulbs.bulb');
    }
}
