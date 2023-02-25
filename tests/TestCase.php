<?php

namespace Nihilsen\Cipher\Tests;

use Nihilsen\Cipher\CipherServiceProvider;
use Nihilsen\Cipher\Tests\Models\User;
use function Orchestra\Testbench\artisan;
use Orchestra\Testbench\Dusk\TestCase as Base;

class TestCase extends Base
{
    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();

        artisan($this, 'migrate', ['--database' => 'sqlite']);

        $this->beforeApplicationDestroyed(
            fn () => artisan($this, 'migrate:rollback', ['--database' => 'sqlite'])
        );
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'sqlite');

        config()->set('app.debug', true);

        config()->set('auth.providers.users.model', User::class);
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
