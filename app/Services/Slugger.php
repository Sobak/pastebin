<?php

namespace App\Services;

use Hashids\Hashids;

class Slugger
{
    /** @var Hashids */
    protected static $hashids;

    public function encode($id)
    {
        if (self::$hashids === null) {
            self::$hashids = $this->init();
        }

        return self::$hashids->encode($id);
    }

    public function decode($slug)
    {
        if (self::$hashids === null) {
            self::$hashids = $this->init();
        }

        return self::$hashids->decode($slug)[0] ?? null;
    }

    protected function init()
    {
        return new Hashids('', 6);
    }
}
