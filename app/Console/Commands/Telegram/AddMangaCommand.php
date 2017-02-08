<?php

namespace App\Console\Commands\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use App\MangaSource;
use DB;

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

        if (count($arguments) < 2) {
           $this->replyWithMessage(['text' => 'Please check the name of the manga and the source '.Emoji::CHARACTER_CRYING_FACE]);
        } else {
            $manga_name  = trim($arguments[0]);
            $source_name = trim($arguments[1]);
            $chat_id     = $this->getUpdate()->getMessage()->getChat()->getId();

            $manga_source = MangaSource::whereHas('manga', function ($manga) use ($manga_name) {
                $manga->where('name', $manga_name);
            })->whereHas('source', function ($source) use ($source_name) {
                $source->where('name', $source_name);
            })->first();

            if (is_null($manga_source)) {
                $this->replyWithMessage(['text' => 'Please check the name of the manga and the source '.Emoji::CHARACTER_CRYING_FACE]);
            } else {
                $already_subscribed = DB::table('subscriptions')->where('manga_source_id', $manga_source->id)
                ->where('telegram_chat_id', $chat_id)
                ->count();

                if ($already_subscribed) {
                    $this->replyWithMessage(['text' => 'Already subscribed '.Emoji::CHARACTER_GRIMACING_FACE]);
                } else {
                    DB::table('subscriptions')->insert(['manga_source_id' => $manga_source->id, 'telegram_chat_id' => $chat_id]);

                    $this->replyWithMessage(['text' => 'Manga '. $manga_name . ' of '. $source_name .' added successfully '.Emoji::CHARACTER_SMILING_FACE_WITH_SUNGLASSES]);
                }
            }
        }
    }
}
