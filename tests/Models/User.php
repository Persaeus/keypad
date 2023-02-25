<?php

namespace Nihilsen\Cipher\Tests\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Nihilsen\Cipher\Cipherable;

class User extends Authenticatable
{
    use Cipherable;

    public $timestamps = false;

    public const EMAIL = 'foo@bar.invalid';

    public const PLAINTEXT_PASSWORD = 'mysecretpassword';

    public static function test()
    {
        return static::firstWhere('email', User::EMAIL);
    }
}
