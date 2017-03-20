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
        $update  = $this->getUpdate();
        $message = $update->getMessage();
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

        $keyboard = Keyboard::make()->inline()
        ->row(
            Keyboard::inlineButton(['text' => 'See sources '.Emoji::CHARACTER_TRADE_MARK_SIGN, 'callback_data' => '/see_sources']),
            Keyboard::inlineButton(['text' => 'My Mangas '.Emoji::CHARACTER_BOOKS, 'callback_data' => '/my_mangas'])
        );

        if ($update->isType('callback_query')) {
            $query = $update->getCallbackQuery();

            $this->getTelegram()->editMessageText([
                'text'         => 'Menu',
                'message_id'   => $query->getMessage()->getMessageId(),
                'chat_id'      => $query->getMessage()->getChat()->getId(),
                'reply_markup' => $keyboard,
            ]);
        } else  {
            $this->replyWithMessage(['text' => 'Menu', 'reply_markup' => $keyboard]);
        }
    }
}
