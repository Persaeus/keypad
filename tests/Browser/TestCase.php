<?php

namespace Nihilsen\Cipher\Tests\Browser;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Nihilsen\Cipher\Facades\Cipher;
use Nihilsen\Cipher\Tests\Models\User;
use Nihilsen\Cipher\Tests\TestCase as Base;

class TestCase extends Base
{
    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tweakApplication(function () {
            static::createTestUser();
            static::setUpRoutes();
        });
    }

    protected static function createTestUser()
    {
        $hashedPassword = hash('sha256', Cipher::salt().User::PLAINTEXT_PASSWORD);

        User::unguarded(fn () => User::firstOrCreate(
            ['email' => User::EMAIL],
            [
                'name' => 'Foobar',
                'password' => Hash::make($hashedPassword),
            ],
        ));
    }

    protected static function setUpRoutes()
    {
        Route::middleware('web')->group(function () {
            Route::get('/browser-tests', function () {
                return View::file(__DIR__.'/views/example.blade.php');
            });

            Route::get('/auth-check', function () {
                return View::file(__DIR__.'/views/auth-check.blade.php');
            });

            Route::get('/login', function () {
                return View::file(__DIR__.'/views/login.blade.php');
            });

            Route::post('/login', function (Request $request) {
                if (
                    ($user = User::whereEmail($request->input('email'))->first()) &&
                    Hash::check(
                        $request->input('password'),
                        $user->password
                    )
                ) {
                    Auth::setUser($user);

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

            Route::get('/encrypt', function () {
                return View::file(__DIR__.'/views/encrypt.blade.php');
            });
        });
    }
}
