<?php

namespace Nihilsen\Cipher\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
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
    }
}
