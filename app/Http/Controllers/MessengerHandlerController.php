<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Source;
use App\Manga;

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

            if ($payload == 'see_sources') {
                $this->sendSources();
            }

            $payload = explode(' ', $payload);

            if (trim($payload[0]) == 'see_mangas') {
                $this->sendMangasOf(trim($payload[1]));
            }

            return 'ok';
        }

        $message   = $messaging['message'];

        if (isset($message['quick_reply'])) {
            $quick_reply = $message['quick_reply'];
            if (isset($quick_reply['payload'])) {
                switch ($quick_reply['payload']) {
                    case 'DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_SQUIRTLE':
                        $this->sendOnlyText('Escuero Escuero');
                    break;
                    case 'DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_BULBASAUR':
                        $this->sendOnlyText('Bolba sar');
                    break;
                    case 'DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_CHARMANDER':
                        $this->sendOnlyText('Char char');
                    break;
                }
            }
        } else {
            if ($message['text'] == 'start') {
                $this->askForStarter();
            }
        }
    }

    private function askForStarter()
    {
        $data = [
            'recipient' => [
                'id' => $this->sender_id,
            ],
            'message' => [
                'text' => 'Choose your starter:',
                'quick_replies' => [
                    [
                        'content_type' => 'text',
                        'title'   =>'Charmander',
                        'payload' => 'DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_CHARMANDER'
                    ],
                    [
                        'content_type' => 'text',
                        'title'   => 'Bulbasaur',
                        'payload' => 'DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_BULBASAUR'
                    ],
                    [
                        'content_type' => 'text',
                        'title'   => 'Squirtle',
                        'payload' => 'DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_SQUIRTLE'
                    ]
                ]
            ],
        ];

        $this->sendMessage($data);
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
