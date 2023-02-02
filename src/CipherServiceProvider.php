<?php

namespace Nihilsen\Cipher;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Nihilsen\Cipher\Commands\CipherCommand;

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
            ->name('cipher')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_cipher_table')
            ->hasCommand(CipherCommand::class);
    }
}
