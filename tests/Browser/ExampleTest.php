<?php

namespace Nihilsen\Cipher\Tests\Browser;

use Laravel\Dusk\Browser;

class ExampleTest extends TestCase
{
    /**
     * A basic browser test example.
     */
    public function test_can_run_browser_tests()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/browser-tests')->assertSee("let's get testing!");
        });
    }
}
