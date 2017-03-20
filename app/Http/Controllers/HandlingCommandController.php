<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Spatie\Emoji\Emoji;
use Illuminate\Http\Request;
use Telegram;

class HandlingCommandController extends Controller
{
    public function handle(Request $request)
    {
        $commands = [
            'see_mangas',
            'see_sources',
            'start',
            'my_mangas',
        ];

        $update = Telegram::commandsHandler(true);

        if ($update->isType('callback_query')) {
            $query = $update->getCallbackQuery();
            $data  = $query->getData();
            $start = strpos($data, ' ');

            $command = ($start !== false) ? substr($data, 1, $start - 1) : substr($data, 1);

            if (in_array($command, $commands)) {
                $update->put('message', collect([
                    'text' => substr($data, $start + 1),
                    'from' => $query->getMessage()->getFrom(),
                    'chat' => $query->getMessage()->getChat()
                ]));

                Telegram::triggerCommand($command, $update);
            }
        }

        return response([], 200);
    }
}
