<?php

namespace Nihilsen\Cipher\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Nihilsen\Cipher\Cipher
 */
class Cipher extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Nihilsen\Cipher\Cipher::class;
    }
}
