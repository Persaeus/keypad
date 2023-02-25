<?php

namespace Nihilsen\Cipher\Tests\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public $timestamps = false;

    public const EMAIL = 'foo@bar.invalid';

    public const PLAINTEXT_PASSWORD = 'mysecretpassword';

    public static function test()
    {
        return static::firstWhere('email', User::EMAIL);
    }
}
