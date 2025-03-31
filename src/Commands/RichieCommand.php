<?php

namespace Awcodes\Richie\Commands;

use Illuminate\Console\Command;

class RichieCommand extends Command
{
    public $signature = 'richie';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
