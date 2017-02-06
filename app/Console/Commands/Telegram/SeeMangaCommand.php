<?php

namespace App\Console\Commands\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use App\Manga;

class SeeMangaCommand extends Command
{
    protected $name = 'see_mangas';

    protected $description = 'Add manga and source that you wants';

    public function handle($arguments)
    {
        $mangas = "";

        Manga::pluck('name')->each(function ($manga) use (&$mangas) {
            $mangas .= $manga."\n";
        });

        $this->replyWithMessage(['text' => $mangas]);
    }
}
