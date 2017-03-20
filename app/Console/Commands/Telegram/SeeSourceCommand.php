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
        $sources = Source::pluck('name')->getIterator();

        for ($i = 0; $i < $sources->count(); $i += 2) {
            if (isset($sources[$i+1])) {
                $keyboard->row(
                    Keyboard::inlineButton(['text' => $sources[$i], 'callback_data' => '/see_mangas '.$sources[$i]]),
                    Keyboard::inlineButton(['text' => $sources[$i+1], 'callback_data' => '/see_mangas '.$sources[$i+1]])
                );
            } else {
                $keyboard->row(
                    Keyboard::inlineButton(['text' => $sources[$i], 'callback_data' => '/see_mangas '.$sources[$i]])
                );
            }
        }
        $keyboard->row(Keyboard::inlineButton(['text' => 'Back to Menu', 'callback_data' => '/start']));

        if ($update->isType('callback_query')) {
            $query = $update->getCallbackQuery();

            $this->getTelegram()->editMessageText([
                'message_id'   => $query->getMessage()->getMessageId(),
                'chat_id'      => $query->getMessage()->getChat()->getId(),
                'reply_markup' => $keyboard,
                'text'         => 'Sources available',
            ]);
        } else {
            $this->replyWithMessage(['text' => 'Sources available', 'reply_markup' => $keyboard]);
        }
    }
}
