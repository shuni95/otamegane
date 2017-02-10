<?php

namespace App\Console\Commands\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use App\MangaSource;
use App\Subscription;

use Spatie\Emoji\Emoji;

class AddMangaCommand extends Command
{
    protected $name = 'add_manga';

    protected $description = "Add manga and source that you wants, please select of the list.\n".
    "with the next format:\n".
    "Manga, Source\n".
    "One Piece, MangaStream";

    public function handle($arguments)
    {
        $arguments = explode(',', $arguments);
        $message = "";

        if (count($arguments) < 2) {
           $message = 'Please check the name of the manga and the source '.Emoji::CHARACTER_CRYING_FACE;
        } else {
            $manga_name  = trim($arguments[0]);
            $source_name = trim($arguments[1]);
            $telegram_chat_id     = $this->getUpdate()->getMessage()->getChat()->getId();

            $manga_source = MangaSource::getMangaInSource($manga_name, $source_name);

            if (is_null($manga_source)) {
                $message = "Please check the name of the manga and the source ".Emoji::CHARACTER_CRYING_FACE;
            } else {
                $manga_source_id = $manga_source->id;
                if (Subscription::alreadySubscribed($manga_source_id, $telegram_chat_id)) {
                    $message = "Already subscribed ".Emoji::CHARACTER_GRIMACING_FACE;
                } else {
                    Subscription::create(compact('manga_source_id', 'telegram_chat_id'));

                    $message = "Manga $manga_name of $source_name added successfully ".Emoji::CHARACTER_SMILING_FACE_WITH_SUNGLASSES;
                }
            }
        }

        $this->replyWithMessage(['text' => $message]);
    }
}
