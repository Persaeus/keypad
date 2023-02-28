<?php

namespace Nihilsen\Keypad\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Nihilsen\Keypad\Keypad resolve()
 * @method static string salt()
 *
 * @see \Nihilsen\Keypad\Keypad
 */
class Keypad extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Nihilsen\Keypad\Keypad::class;
    }
}
