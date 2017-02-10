<?php

namespace App\Console\Commands\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use App\Source;
use App\Manga;
use App\Suggestion;
use Spatie\Emoji\Emoji;

class SuggestMangaCommand extends Command
{
    protected $name = 'suggest_manga';

    protected $description = "Suggest a manga, please write the same name that appear in the source added\n".
    "Nisekoi, TuMangaOnline";

    public function handle($arguments)
    {
        $arguments = explode(',', $arguments);

        if (count($arguments) < 2) {
           $this->replyWithMessage(['text' => 'Please check the name of the manga and the source '.Emoji::CHARACTER_CRYING_FACE]);
        } else {
            $manga_name  = trim($arguments[0]);
            $source_name = trim($arguments[1]);

            $source = Source::where('name', $source_name)->first();

            if (is_null($source) || ($manga_name == "")) {
                $this->replyWithMessage(['text' => 'Please check the name of the manga and the source '.Emoji::CHARACTER_CRYING_FACE]);
            } else {
                $manga = Manga::where('name', $manga_name)->first();

                if (is_null($manga)) {
                    Suggestion::create([
                        'name' => $manga_name,
                        'source_id' => $source->id,
                    ]);

                    $this->replyWithMessage(['text' => 'Admin will review your suggestion. Thanks for collaborate '.Emoji::CHARACTER_SMILING_FACE_WITH_SMILING_EYES]);
                } else {
                    $this->replyWithMessage(['text' => 'This manga already exists in the source selected '.Emoji::CHARACTER_THINKING_FACE]);
                }
            }
        }
    }
}
