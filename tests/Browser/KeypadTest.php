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

    public function test_can_encrypt_change_password_and_then_decrypt()
    {
        $this->browse(
            function (Browser $browser) {
                $browser
                    // Register
                    ->visit('/register')
                    ->type('@email-field', $email = 'foobar@qux.invalid')
                    ->type('@password-field', User::PLAINTEXT_PASSWORD)
                    ->clickAndWaitForReload('@submit-button');

                // Determine the client-side hash for later comparison
                $hash = $browser->driver->executeScript('return localStorage.keypad');
                $this->assertNotEmpty($hash);

                $browser
                    // Log in
                    ->loginAs(User::firstWhere('email', $email))

                    // Encrypt a message to self
                    ->visit('/encrypt')
                    ->type('@plaintext-field', $message = "what's in the box?")
                    ->clickAndWaitForReload('@submit-button');

                // Store the encrypted message
                $query = parse_url($browser->driver->getCurrentURL(), PHP_URL_QUERY);

                $browser
                    // Change password
                    ->visit('/change-password')
                    ->type('@password-field', $newPassword = 'mynewsecretpassword')
                    ->type('@confirm-password-field', $newPassword)
                    ->clickAndWaitForReload('@submit-button')

                    // Log out and back in again to clear local storage
                    ->logout()
                    ->visit('/login')
                    ->type('@email-field', $email)
                    ->type('@password-field', $newPassword)
                    ->clickAndWaitForReload('@submit-button')

                    // Assert that stored client-side hash has changed
                    ->assertScript("localStorage.keypad != '$hash'")

                    // Decrypt the message from earlier
                    ->visit("/decrypt?$query")
                    ->waitForText($message, 3)
                    ->assertSee($message);
            }
        );
    }

    public function test_can_encrypt_and_attempt_to_change_password_handle_failure_and_still_decrypt()
    {
        $this->browse(
            function (Browser $browser) {
                $browser
                    // Register
                    ->visit('/register')
                    ->type('@email-field', $email = 'baz@foobar.invalid')
                    ->type('@password-field', User::PLAINTEXT_PASSWORD)
                    ->clickAndWaitForReload('@submit-button')

                    // Log in
                    ->loginAs(User::firstWhere('email', $email))

                    // Encrypt a message to self
                    ->visit('/encrypt')
                    ->type('@plaintext-field', $message = 'You\'re head of security and your password is "password"?')
                    ->clickAndWaitForReload('@submit-button');

                // Store the encrypted message
                $query = parse_url($browser->driver->getCurrentURL(), PHP_URL_QUERY);

                $browser
                    // Attempt to change password, but intentionally fail the confirmation check
                    ->visit('/change-password')
                    ->type('@password-field', 'mynewsecretpassword')
                    ->type('@confirm-password-field', 'notmynewsecretpassword')
                    ->clickAndWaitForReload('@submit-button')

                    // Check that the message from earlier can still be decrypted
                    ->visit("/decrypt?$query")
                    ->waitForText($message, 3)
                    ->assertSee($message);
            }
        );
    }
}
