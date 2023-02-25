<?php

namespace Nihilsen\Cipher\Tests\Browser;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Nihilsen\Cipher\Facades\Cipher;
use Nihilsen\Cipher\Tests\TestCase as Base;

class TestCase extends Base
{
    public const PLAINTEXT_PASSWORD = 'mysecretpassword';

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tweakApplication(function () {
            Route::get('/browser-tests', function () {
                return View::file(__DIR__.'/views/example.blade.php');
            });

            Route::get('/login', function () {
                return View::file(__DIR__.'/views/login.blade.php');
            });

            Route::post('/login', function (Request $request) {
                // Check that password is a sha256-digest of the plaintext password with the base salt prepended.
                if ($request->input('password') == hash('sha256', Cipher::salt().static::PLAINTEXT_PASSWORD)) {
                    return redirect('/logged-in');
                }

                return redirect('/failed-login');
            });

            Route::get('/logged-in', function () {
                return 'logged in';
            });

            Route::get('/failed-login', function () {
                return 'failed login';
            });
        });
    }
}
