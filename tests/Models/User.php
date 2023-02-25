<?php

namespace Nihilsen\Cipher\Tests\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Nihilsen\Cipher\Facades\Cipher;
use Sushi\Sushi;

class User extends Authenticatable
{
    use Sushi;

    public const EMAIL = 'foo@bar.invalid';

    public const PLAINTEXT_PASSWORD = 'mysecretpassword';

    public function getRows()
    {
        $hashedPassword = hash('sha256', Cipher::salt().static::PLAINTEXT_PASSWORD);

        return [
            [
                'email' => static::EMAIL,
                'password' => Hash::make($hashedPassword),
            ],
        ];
    }
}
