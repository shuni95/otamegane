<?php

namespace App\Services;

use App\MessengerChat;

class MessengerService
{
    protected $sender_id;

    public function __construct($sender_id)
    {
        $this->sender_id = $sender_id;
    }

    public function sendText($text, $quick_replies = [])
    {
        $message['text'] = $text;
        if ($quick_replies) {
            $message['quick_replies'] = $quick_replies;
        }

        $data = [
            'recipient' => [
                'id' => $this->sender_id,
            ],
            'message' => $message
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

    public function sendMessageWithButtons($text, $buttons)
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

    public function sendGenericTemplate($elements)
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

    public function start()
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

        $this->sendText('Hi '.$user->first_name.', I am OtameganeBot. Please use the menu.');
    }
}
