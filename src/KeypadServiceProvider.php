<?php

namespace Nihilsen\Keypad;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View as Renderable;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Nihilsen\Keypad\Controllers\KeypadJavaScriptAssets;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class KeypadServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name($name = 'keypad')
            ->hasViews($name)
            ->hasMigration('0000_00_00_000001_create_keypads_table')
            ->runsMigrations()
            ->hasConfigFile();
    }

    /**
     * {@inheritDoc}
     */
    public function packageBooted()
    {
        // Share the $keypad variable with all keypad views, except when already set.
        View::composer("{$this->package->name}::*", function (Renderable $view) {
            $view->with('keypad', $view->keypad ?? $this->app->make(Keypad::class));
        });
    }

    /**
     * {@inheritDoc}
     */
    public function packageRegistered()
    {
        $this->registerSingleton();
        $this->registerRoutes();
    }

    protected function registerSingleton()
    {
        $this->app->afterResolving('auth', fn () => $this->app->singleton(
            Keypad::class,
            function (Application $app) {
                $user = $app->request->user();

                if (
                    $user &&
                    array_key_exists(Keypadded::class, class_uses_recursive($user))
                ) {
                    /** @var \Nihilsen\Keypad\Keypadded $user */
                    return $user->keypad;
                }

                return new Keypad;
            }
        ));
    }

    protected function registerRoutes()
    {
        Route::get('/keypad/keypad.js', [KeypadJavaScriptAssets::class, 'source']);
        Route::get('/keypad/keypad.js.map', [KeypadJavaScriptAssets::class, 'maps']);
    }
}
