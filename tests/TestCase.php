<?php

namespace Nihilsen\Cipher\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Nihilsen\Cipher\CipherServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
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
