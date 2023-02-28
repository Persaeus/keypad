<?php

namespace Nihilsen\Keypad\Tests\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Nihilsen\Keypad\Keypadded;

class User extends Authenticatable
{
    use Keypadded;

    public $timestamps = false;

    public const EMAIL = 'foo@bar.invalid';

    public const PLAINTEXT_PASSWORD = 'mysecretpassword';

    public static function test()
    {
        return static::firstWhere('email', User::EMAIL);
    }
}
