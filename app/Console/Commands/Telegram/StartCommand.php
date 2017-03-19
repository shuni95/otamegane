<?php

namespace App\Console\Commands\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;

use App\TelegramChat;
use App\Source;
use Spatie\Emoji\Emoji;

class StartCommand extends Command
{
    protected $name = 'start';

    protected $description = 'When starts the bot your user is registered in db for next requests.';

    public function handle($arguments)
    {
        $message = $this->getUpdate()->getMessage();
        $from = $message->getFrom();
        $chat = $message->getChat();

        $telegram_user = TelegramChat::find($chat->getId());

        if (is_null($telegram_user)) {
            if ($chat->getType() == 'private') {
                TelegramChat::create([
                    'chat_id'    => $chat->getId(),
                    'first_name' => $from->getFirstName(),
                    'last_name'  => $from->getLastName(),
                    'username'   => $from->getUsername(),
                    'type'       => $chat->getType(),
                ]);

                $this->replyWithMessage(['text' => 'Welcome '.$from->getFirstName().'! '.Emoji::CHARACTER_GRINNING_FACE_WITH_SMILING_EYES]);
            } else {
                TelegramChat::create([
                    'chat_id' => $chat->getId(),
                    'title'   => $chat->getTitle(),
                    'type'    => $chat->getType(),
                ]);

                $this->replyWithMessage(['text' => 'Hi guys'.Emoji::CHARACTER_GRINNING_FACE_WITH_SMILING_EYES]);
            }
        }

        $keyboard = Keyboard::make()->inline();
        Source::pluck('name')->each(function ($source) use ($keyboard) {
            $keyboard->row(Keyboard::inlineButton(['text' => $source, 'callback_data' => '/see_mangas '.$source]));
        });

        $this->replyWithMessage(['text' => 'Sources available', 'reply_markup' => $keyboard]);
    }
}
