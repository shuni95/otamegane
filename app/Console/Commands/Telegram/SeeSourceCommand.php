<?php

namespace App\Console\Commands\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;

use App\Source;

class SeeSourceCommand extends Command
{
    protected $name = 'see_sources';

    protected $description = 'Show all the sources';

    public function handle($arguments)
    {
        $update = $this->getUpdate();

        $keyboard = Keyboard::make()->inline();
        Source::pluck('name')->each(function ($source) use ($keyboard) {
            $keyboard->row(Keyboard::inlineButton(['text' => $source, 'callback_data' => '/see_mangas '.$source]));
        });


        if ($update->isType('callback_query')) {
            $query = $update->getCallbackQuery();

            $this->getTelegram()->editMessageText([
                'message_id' => $query->getMessage()->getMessageId(),
                'chat_id' => $query->getMessage()->getChat()->getId(),
                'reply_markup' => $keyboard,
                'text' => 'Sources available',
            ]);
        } else {
            $this->replyWithMessage(['text' => 'Sources available', 'reply_markup' => $keyboard]);
        }
    }
}
