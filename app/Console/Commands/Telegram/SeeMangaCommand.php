<?php

namespace App\Console\Commands\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use App\Manga;

class SeeMangaCommand extends Command
{
    protected $name = 'see_mangas';

    protected $description = 'See all the mangas of a source, write the source please.';

    public function handle($arguments)
    {
        if (strlen($arguments) > 0) {
            $mangas = Manga::belongsSource($arguments)->pluck('name')->implode("\n");

            $this->replyWithMessage(['text' => $mangas]);
        } else {
            $this->replyWithMessage(['text' => 'Please write a valid source.']);
        }
    }
}
