<?php

namespace Nihilsen\Keypad\Tests\Browser;

use Laravel\Dusk\Browser;
use Nihilsen\Keypad\Tests\Models\User;

class KeypadTest extends TestCase
{
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

    public function test_can_log_in_as_test_user()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::test())
                ->visit('/auth-check')
                ->assertSee('logged in');
        });
    }

    public function test_can_register_and_log_in_and_encrypt_and_decrypt_once_logged_in()
    {
        $this->browse(
            fn (Browser $browser) => $browser
                // Register
                ->visit('/register')
                ->type('@email-field', $email = 'bar@qux.invalid')
                ->type('@password-field', User::PLAINTEXT_PASSWORD)
                ->clickAndWaitForReload('@submit-button')

                // Log in
                ->loginAs(User::firstWhere('email', $email))

                // Encrypt a message to self
                ->visit('/encrypt')
                ->type('@plaintext-field', $message = 'the cake is a lie')
                ->clickAndWaitForReload('@submit-button')

                // Decrypt the message
                ->assertPathIs('/decrypt')
                ->waitForText($message, 3)
                ->assertSee($message)
        );
    }
}
