<?php

namespace App;

use TheJawker\ControlStuff\LedFlux\BulbScanner as HardwareBulbScanner;

class BulbScanner
{
    public function discover()
    {
        $scanner = new HardwareBulbScanner();
        $scanner->scan();

        return $scanner->discoveredLights;
    }
}