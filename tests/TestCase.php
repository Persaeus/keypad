<?php

namespace Nihilsen\Cipher\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Nihilsen\Cipher\CipherServiceProvider;
use Orchestra\Testbench\Dusk\TestCase as Base;

class TestCase extends Base
{
    use RefreshDatabase;

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        // config()->set('app.debug', true);
    }

    protected function getPackageProviders($app)
    {
        return [
            CipherServiceProvider::class,
        ];
    }

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

            Route::post('/login', function () {
            });
        });
    }
}
