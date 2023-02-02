<?php

namespace Nihilsen\Cipher\Commands;

use Illuminate\Console\Command;

class CipherCommand extends Command
{
    public $signature = 'cipher';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
