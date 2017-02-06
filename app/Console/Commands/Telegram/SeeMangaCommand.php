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
        $mangas = "";

        if (strlen($arguments) > 0) {
            Manga::whereHas('sources', function ($source) use ($arguments) {
                $source->where('name', $arguments);
            })
            ->pluck('name')->each(function ($manga) use (&$mangas) {
                $mangas .= $manga."\n";
            });

            $this->replyWithMessage(['text' => $mangas]);
        } else {
            $this->replyWithMessage(['text' => 'Please write a valid source.']);
        }
    }
}
