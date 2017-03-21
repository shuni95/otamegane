<?php

namespace App\Console\Commands\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;

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

    /**
     * Ask if the user want to add a manga to its mangas when only send the manga and source
     * Add the subscription when answer to the question
     * @param string $arguments Separated by commas, [0] => Manga, [1] => Source, [2] => Answer
     * @return type
     */
    public function handle($arguments)
    {
        $arguments = explode(',', $arguments);
        $update    = $this->getUpdate();
        $keyboard  = Keyboard::make()->inline();

        $text = 'Please check the name of the manga and the source '.Emoji::CHARACTER_CRYING_FACE;

        $manga_name  = trim($arguments[0]);
        $source_name = trim($arguments[1]);
        $chat_id     = $update->getChat()->getId();

        if (isset($arguments[2])) {
            $answer = trim($arguments[2]);

            if ($answer == 'YES') {
                $manga_source = MangaSource::getMangaInSource($manga_name, $source_name);

                if (is_null($manga_source)) {
                    $text = "Please check the name of the manga and the source ".Emoji::CHARACTER_CRYING_FACE;
                } else {
                    if (Subscription::alreadySubscribed($manga_source->id, $chat_id)) {
                        $text = "Already subscribed ".Emoji::CHARACTER_GRIMACING_FACE;
                    } else {
                        Subscription::create([
                            'manga_source_id' => $manga_source->id,
                            'telegram_chat_id' => $chat_id
                        ]);

                        $text = "Manga $manga_name of $source_name added successfully ".Emoji::CHARACTER_SMILING_FACE_WITH_SUNGLASSES;
                    }
                }
            }
        } else {
            $text = "Do you want subscribe to $manga_name of $source_name?";

            $keyboard->row([
                'text' => "Yes, I want it!",
                'callback_data' => "/add_manga $manga_name, $source_name, YES"
            ],[
                'text' => "No, I was playing with the bot",
                'callback_data' => "/see_mangas $source_name"
            ]);
        }

        $keyboard->row(Keyboard::inlineButton([
            'text' => "Back to Mangas of $source_name",
            'callback_data' => "/see_mangas $source_name"
        ]));

        if ($update->isType('callback_query')) {
            $query = $update->getCallbackQuery();

            $this->getTelegram()->editMessageText([
                'message_id'   => $query->getMessage()->getMessageId(),
                'chat_id'      => $chat_id,
                'reply_markup' => $keyboard,
                'text'         => $text,
            ]);
        } else {
            $this->replyWithMessage(['text' => $text, 'reply_markup' => $keyboard]);
        }
    }
}
