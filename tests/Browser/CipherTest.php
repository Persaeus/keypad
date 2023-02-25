<?php

namespace Nihilsen\Cipher\Tests\Browser;

use Laravel\Dusk\Browser;

class CipherTest extends TestCase
{
    public function test_can_intercept_and_hash_passwords_for_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('@password-field', static::PLAINTEXT_PASSWORD)
                ->press('Log in')
                ->pause(3000)
                ->assertPathIs('/logged-in');
        });
    }
}
