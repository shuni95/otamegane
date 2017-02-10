<?php

namespace App\Console\Commands\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use App\Source;

class SeeSourceCommand extends Command
{
    protected $name = 'see_sources';

    protected $description = 'Show all the sources';

    public function handle($arguments)
    {
        $sources = Source::pluck('name')->implode("\n");

        $this->replyWithMessage(['text' => $sources]);
    }
}
