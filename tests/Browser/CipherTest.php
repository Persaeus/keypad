<?php

namespace Nihilsen\Cipher\Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Nihilsen\Cipher\Tests\Models\User;

class CipherTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_intercept_and_hash_passwords_for_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('@email-field', User::EMAIL)
                ->type('@password-field', User::PLAINTEXT_PASSWORD)
                ->clickAndWaitForReload('@submit-button')
                ->assertPathIs('/logged-in');
        });
    }
}
