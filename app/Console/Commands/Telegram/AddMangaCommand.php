<?php

namespace App\Console\Commands\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use App\MangaSource;
use App\TelegramUser;
use DB;

use Spatie\Emoji\Emoji;

class AddMangaCommand extends Command
{
    protected $name = 'add_manga';

    protected $description = 'Add manga and source that you wants';

    public function handle($arguments)
    {
        $arguments   = explode(',', $arguments);
        $manga_name  = trim($arguments[0]);
        $source_name = trim($arguments[1]);

        $user_id     = $this->getUpdate()->getMessage()->getFrom()->getId();
        $manga_source = MangaSource::whereHas('manga', function ($manga) use ($manga_name) {
            $manga->where('name', $manga_name);
        })->whereHas('source', function ($source) use ($source_name) {
            $source->where('name', $source_name);
        })->first();

        if (is_null($manga_source)) {
            $this->replyWithMessage(['text' => 'Please check the name of the manga and the source '.Emoji::CHARACTER_CRYING_FACE]);
        } else {
            $already_subscribed = DB::table('subscriptions')->where('manga_source_id', $manga_source->id)
            ->where('telegram_user_id', $user_id)
            ->count();

            if ($already_subscribed) {
                $this->replyWithMessage(['text' => 'Already subscribed '.Emoji::CHARACTER_GRIMACING_FACE]);
            } else {
                $telegram_user = TelegramUser::where('user_id', $user_id)->first();

                DB::table('subscriptions')->insert(['manga_source_id' => $manga_source->id, 'telegram_user_id' => $telegram_user->id]);

                $this->replyWithMessage(['text' => 'Manga '. $manga_name . ' of '. $source_name .' added successfully '.Emoji::CHARACTER_SMILING_FACE_WITH_SUNGLASSES]);
            }
        }
    }
}
