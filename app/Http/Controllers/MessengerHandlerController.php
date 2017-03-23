<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Source;
use App\Manga;
use App\MessengerChat;

class MessengerHandlerController extends Controller
{
    protected $sender_id;

    public function verify(Request $request)
    {
        return $request->input('hub_challenge');
    }

    public function handle(Request $request)
    {
        \Log::info(print_r($request->all(), true));

        $entry = $request->input('entry');
        $messaging = $entry[0]['messaging'][0];
        $this->sender_id = $messaging['sender']['id'];

        if (isset($messaging['delivery'])) {
            return 'ok';
        } elseif (isset($messaging['postback'])) {
            $payload = $messaging['postback']['payload'];

            switch ($payload) {
                case 'start':       $this->start();        break;
                case 'see_sources': $this->sendSources();  break;
                case 'my_mangas':   $this->sendMyMangas(); break;
            }

            $payload = explode(' ', $payload);
            $first_arg = trim($payload[0]);

            if ($first_arg == 'see_mangas') {
                $this->sendMangasOf(trim($payload[1]));
            }

            if ($first_arg == 'add_manga') {
                if (isset($payload[3])) {
                    if ($payload[3] == 'YES') {
                        $this->addSubscription($payload[1], $payload[2]);
                    }
                }
                $this->sendQuestionSubscription($payload[1], $payload[2]);
            }

            return 'ok';
        }
    }

    private function start()
    {
        $ch = curl_init('https://graph.facebook.com/v2.6/'.$this->sender_id.'?access_token='.env('MESSENGER_ACCESS_TOKEN'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $user = json_decode($result);

        MessengerChat::create([
            'chat_id' => $this->sender_id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'locale' => $user->locale,
            'gender' => $user->gender,
        ]);

        $this->sendOnlyText('Hi '.$user->first_name.', I am OtameganeBot. Please use the menu.');
    }

    private function sendOnlyText($message)
    {
        $data = [
            'recipient' => [
                'id' => $this->sender_id,
            ],
            'message' => [
                'text' => $message,
            ]
        ];

        $this->sendMessage($data);
    }

    /**
     * Send to api messenger
     * @param array $data
     * @return void
     */
    private function sendMessage($data)
    {
        $data_string = json_encode($data);

        $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.env('MESSENGER_ACCESS_TOKEN'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        \Log::info(print_r($result, true));
        curl_close($ch);
    }

    private function sendMessageWithButtons($text, $buttons)
    {
        $data = [
            'recipient' => [
                'id' => $this->sender_id,
            ],
            'message' => [
                'attachment' => [
                    'type' => 'template',
                    'payload' => [
                        'template_type' => 'button',
                        'text' => $text,
                        'buttons' => $buttons,
                    ]
                ]
            ],
        ];

        $this->sendMessage($data);
    }

    /**
     * Send sources
     * @return void
     */
    private function sendSources()
    {
        $buttons = Source::pluck('name')
        ->map(function ($source) {
            return [
                'type' => "postback",
                'title' => $source,
                'payload' => "see_mangas $source",
            ];
        });

        $this->sendMessageWithButtons('List of sources', $buttons);
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
                        'payload' => "add_manga $manga $source",
                    ]
                ]
            ];
        });

        $this->sendGenericTemplate($elements);
    }

    private function sendGenericTemplate($elements)
    {
        $data = [
            'recipient' => [
                'id' => $this->sender_id,
            ],
            'message' => [
                'attachment' => [
                    'type' => 'template',
                    'payload' => [
                        'template_type' => 'generic',
                        'elements' => $elements,
                    ]
                ]
            ],
        ];

        $this->sendMessage($data);
    }
}
