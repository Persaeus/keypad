<?php

namespace Nihilsen\Cipher\Tests\Browser;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Nihilsen\Cipher\CipherServiceProvider;
use Orchestra\Testbench\Dusk\TestCase as Base;

class TestCase extends Base
{
    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tweakApplication(function () {
            Route::get('/browser-tests', function () {
                return View::file(__DIR__.'/views/test.blade.php');
            });
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            CipherServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_cipher_table.php.stub';
        $migration->up();
        */
    }
}
