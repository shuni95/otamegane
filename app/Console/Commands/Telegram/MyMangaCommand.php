<?php

namespace App\Console\Commands\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use App\TelegramChat;

class MyMangaCommand extends Command
{
    protected $name = 'my_mangas';

    protected $description = 'List my mangas';

    public function handle($arguments)
    {
        $chat_id       = $this->getUpdate()->getMessage()->getChat()->getId();

        $subscriptions = TelegramChat::find($chat_id)->subscriptions->map(function ($subscription) {
            return $subscription->manga->name . " - " . $subscription->source->name;
        })->implode("\n");

        $this->replyWithMessage(['text' => $subscriptions]);
    }
}
