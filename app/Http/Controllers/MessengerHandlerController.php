<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Source;
use App\Manga;
use App\MessengerChat;
use App\MangaSource;
use App\Subscription;
use App\Services\MessengerService;

class MessengerHandlerController extends Controller
{
    protected $sender;

    protected $messaging;

    protected $chat_id;

    public function __construct(Request $request)
    {
        \Log::info(print_r($request->all(), true));
        if ($request->method() == 'POST') {
            $entry = $request->input('entry');
            $this->messaging = $entry[0]['messaging'][0];
            $this->sender    = new MessengerService;
            $this->chat_id   = $this->messaging['sender']['id'];
        }
    }

    public function verify(Request $request)
    {
        return $request->input('hub_challenge');
    }

    public function handle(Request $request)
    {
        if (isset($this->messaging['delivery'])) {
            return 'ok';
        } elseif (isset($this->messaging['postback'])) {
            $payload = $this->messaging['postback']['payload'];

            switch ($payload) {
                case 'start':       $this->sender->start($this->chat_id);        break;
                case 'see_sources': $this->sendSources();  break;
                case 'my_mangas':   $this->sendMyMangas(); break;
            }

            $payload = explode(',', $payload);

            if (trim($payload[0]) == 'see_mangas') {
                $this->sendMangasOf(trim($payload[1]));
            }

            if (trim($payload[0]) == 'add_manga') {
                $this->sendQuestionSubscription($payload[1], $payload[2]);
            }

            return 'ok';
        } elseif (isset($this->messaging['message'])) {
            $message = $this->messaging['message'];

            if (isset($message['quick_reply'])) {
                $payload = $message['quick_reply']['payload'];

                $payload = explode(',', $payload);

                if (trim($payload[0]) == 'add_manga' && trim($payload[3]) == 'YES') {
                    $this->addSubscription(trim($payload[1]), trim($payload[2]));
                }
            }
        }
    }

    /**
     * Send sources
     * @return void
     */
    private function sendSources()
    {
        $elements = Source::pluck('name')
        ->map(function ($source) {
            return [
                'title' => $source,
                'buttons' => [
                    [
                        'type' => "postback",
                        'title' => $source,
                        'payload' => "see_mangas, $source",
                    ]
                ]
            ];
        });

        $this->sender->sendGenericTemplate($this->chat_id, $elements);
    }

    private function sendMangasOf($source)
    {
        $elements = Manga::belongsSource($source)->pluck('name')
        ->map(function ($manga) use ($source){
            return [
                'title' => $manga,
                'image_url' => 'https://s-media-cache-ak0.pinimg.com/originals/81/6c/79/816c79d251726a0de313011309281a74.png',
                'buttons' => [
                    [
                        'type' => "postback",
                        'title' => 'Add to subscriptions',
                        'payload' => "add_manga, $manga, $source",
                    ]
                ]
            ];
        });

        $this->sender->sendGenericTemplate($this->chat_id, $elements);
    }

    private function sendQuestionSubscription($manga, $source)
    {
        $message = "Do you want subscribe to $manga of $source?";

        $quick_replies = [
            [
                'content_type' => 'text',
                'title' => 'Yes, I want it',
                'payload' => "add_manga, $manga, $source, YES",
            ],
            [
                'content_type' => 'text',
                'title' => 'No, thanks',
                'payload' => "see_mangas, $source",
            ]
        ];

        $this->sender->sendText($this->chat_id, $message, $quick_replies);
    }

    private function addSubscription($manga, $source)
    {
        $manga_source = MangaSource::getMangaInSource($manga, $source);

        if (is_null($manga_source)) {
            $text = "Please check the name of the manga and the source ";
        } else {
            if (Subscription::alreadySubscribed($manga_source->id, $this->chat_id)) {
                $text = "You are already subscribed.";
            } else {
                Subscription::create([
                    'manga_source_id' => $manga_source->id,
                    'messenger_chat_id' => $this->chat_id
                ]);

                $text = "Manga $manga of $source added successfully";
            }
        }

        $this->sender->sendText($this->chat_id, $text);
    }
}
