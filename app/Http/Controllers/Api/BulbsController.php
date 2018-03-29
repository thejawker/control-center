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
}
