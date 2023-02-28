<?php

namespace Nihilsen\Keypad\Contracts;

use Illuminate\Auth\Events\Registered;

interface Registers
{
    public function register(Registered $event);
}
