<?php

namespace Nihilsen\Cipher;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View as Renderable;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Nihilsen\Cipher\Controllers\CipherJavaScriptAssets;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CipherServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name($name = 'cipher')
            ->hasViews($name)
            ->hasMigration('0000_00_00_000001_create_ciphers_table')
            ->runsMigrations()
            ->hasConfigFile();
    }

    /**
     * {@inheritDoc}
     */
    public function packageBooted()
    {
        // Share the $cipher variable with all cipher views, except when already set.
        View::composer("{$this->package->name}::*", function (Renderable $view) {
            $view->with('cipher', $view->cipher ?? $this->app->make(Cipher::class));
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
        $this->app->singleton(
            Cipher::class,
            function (Application $app) {
                $user = $app->request->user();

                if (
                    $user &&
                    array_key_exists(Cipherable::class, class_uses_recursive($user))
                ) {
                    /** @var \Nihilsen\Cipher\Cipherable $user */
                    return $user->cipher;
                }

                return new Cipher;
            }
        );
    }

    protected function registerRoutes()
    {
        Route::get('/cipher/cipher.js', [CipherJavaScriptAssets::class, 'source']);
        Route::get('/cipher/cipher.js.map', [CipherJavaScriptAssets::class, 'maps']);
    }
}
