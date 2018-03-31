<?php

namespace App\Http\Controllers\Api;

use App\Bulb;
use App\Http\Controllers\Controller;

class BulbsController extends Controller
{
    public function index()
    {
        return response()->json(Bulb::all());
    }

    public function show($id)
    {
        return response()->json(Bulb::find($id));
    }

    public function updateAll()
    {
        if ($settings = $this->settings()) {
            Bulb::all()->each->updateSettings($settings);
        }
    }

    public function update($id)
    {
        $bulb = Bulb::find($id);

        $bulb->update(request()->validate([
            'name' => 'between:4,64'
        ]));

        if ($settings = $this->settings()) {
            $bulb->updateSettings($settings);
        }

        return response()->json($bulb);
    }

    public function settings()
    {
        if (request()->hasAny(['color', 'powered'])) {
            return request()->only(['color', 'powered']);
        }
    }
}
