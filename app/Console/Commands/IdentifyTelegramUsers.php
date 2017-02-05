<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Telegram;
use Log;
use App\TelegramUser;
use Spatie\Emoji\Emoji;

class IdentifyTelegramUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:identify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Identify new telegram users that';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        collect(Telegram::getUpdates())->each(function ($update) {
            $message = $update->getMessage();

            if ($message->getText() == '/start') {
                $from = $message->getFrom();
                $chat = $message->getChat();
                $user_id = $from->getId();
                $telegram_user = TelegramUser::where('user_id', $user_id)->first();

                if (is_null($telegram_user)) {
                    TelegramUser::create([
                        'user_id' => $user_id,
                        'first_name' => $from->getFirstName(),
                        'last_name' => $from->getLastName(),
                        'username' => $from->getUsername(),
                    ]);

                    Telegram::sendMessage([
                        'chat_id' => $chat->getId(),
                        'text' => 'Welcome '.$from->getFirstName().'! '.Emoji::CHARACTER_GRINNING_FACE_WITH_SMILING_EYES,
                    ]);
                }
            }
        });
    }
}
