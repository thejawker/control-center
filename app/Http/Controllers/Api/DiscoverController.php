<?php

namespace App\Http\Controllers\Api;

use App\Bulb;
use Facades\App\BulbScanner;
use App\Http\Controllers\Controller;

class DiscoverController extends Controller
{
    public function store()
    {
        $hardwareBulbs = BulbScanner::discover();

        $bulbs = Bulb::storeHardwareBulbs($hardwareBulbs);

        return response()->json($bulbs, 201);
    }
}