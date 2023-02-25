<?php

namespace Nihilsen\Cipher\Contracts;

use Illuminate\Auth\Events\Registered;

interface Registers
{
    public function register(Registered $event);
}
