<?php

namespace App\Console\Commands\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;

use App\TelegramChat;

use Spatie\Emoji\Emoji;

class MyMangaCommand extends Command
{
    protected $name = 'my_mangas';

    protected $description = 'List my mangas';

    public function handle($arguments)
    {
        $update  = $this->getUpdate();
        $chat_id = $update->getChat()->getId();

        $keyboard = Keyboard::make()->inline();
        $counter = 0;
        TelegramChat::where('chat_id', $chat_id)->first()
        ->subscriptions->each(function ($subscription) use ($keyboard, &$counter) {
            $keyboard->row(Keyboard::inlineButton([
                'text' => $subscription->manga->name . " - " . $subscription->source->name,
                'callback_data' => 'info'
            ]));
            $counter++;
        });
        $keyboard->row(Keyboard::inlineButton(['text' => 'Back to Menu', 'callback_data' => '/start']));

        if ($counter > 0) {
            $text = 'Your subscriptions';
        } else {
            $text = 'You don\'t have any subscriptions to mangas '.Emoji::CHARACTER_CRYING_FACE;
        }

        if ($update->isType('callback_query')) {
            $query = $update->getCallbackQuery();

            $this->getTelegram()->editMessageText([
                'message_id'   => $query->getMessage()->getMessageId(),
                'chat_id'      => $update->getChat()->getId(),
                'reply_markup' => $keyboard,
                'text'         => $text,
            ]);
        } else {
            $this->replyWithMessage(['text' => $text, 'reply_markup' => $keyboard]);
        }
    }
}
