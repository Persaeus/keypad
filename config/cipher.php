<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Salt
    |--------------------------------------------------------------------------
    |
    | The base salt value is used for hashing all passwords.
    |
    | This helps to protect against rainbow table attacks.
    |
    | A minimum of 128-bit (16-byte) is recommended.
    |
    | It should not be changed once set, or it will break
    | authentication for existing users.
    |
    */

    'salt' => env('CIPHER_SALT', 'e3e2d8ea727465c65d20d23716059a61'),

];
