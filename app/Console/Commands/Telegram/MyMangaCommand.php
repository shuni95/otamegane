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
        $subscriptions = "";
        $chat_id       = $this->getUpdate()->getMessage()->getChat()->getId();

        TelegramChat::find($chat_id)->subscriptions->each(function ($subscription) use (&$subscriptions) {
            $subscriptions .= $subscription->manga->name . " - " . $subscription->source->name . "\n";
        });

        $this->replyWithMessage(['text' => $subscriptions]);
    }
}
