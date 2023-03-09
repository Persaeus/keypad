<?php

namespace Nihilsen\Keypad\Tests\Browser;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Nihilsen\Keypad\Facades\Keypad;
use Nihilsen\Keypad\Tests\Models\User;
use Nihilsen\Keypad\Tests\TestCase as Base;

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
        $hashedPassword = hash('sha256', Keypad::salt().User::PLAINTEXT_PASSWORD);

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

            Route::get('/register', function () {
                return View::file(__DIR__.'/views/register.blade.php');
            });

            Route::post('/register', function (Request $request) {
                $newUser = User::unguarded(fn () => DB::transaction(fn () => User::create([
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'password' => Hash::make($request['password']),
                ])));

                // Dispatch "registered" event.
                event(new Registered($newUser));

                Auth::login($newUser);

                return redirect('/logged-in');
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
                    Auth::login($user);

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

            Route::get('/decrypt', function () {
                return View::file(__DIR__.'/views/decrypt.blade.php');
            });

            Route::get('/change-password', function () {
                return View::file(__DIR__.'/views/change-password.blade.php');
            });

            Route::post('/change-password', function (Request $request) {
                if ($request['password'] == $request['password_confirmation']) {
                    $request->user()->forceFill([
                        'password' => Hash::make($request['password']),
                    ])->save();
                }

                return back();
            });
        });
    }
}
