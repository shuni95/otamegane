<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['manga', 'chapter', 'title', 'status', 'source_id', 'url'];

    public function scopePrevious($query, $manga, $chapter, $source_id)
    {
        return $query->where('manga', $manga)
                     ->where('chapter', $chapter)
                     ->where('source_id', $source_id);
    }

    public static function last($manga, $source_id)
    {
        return (new static())::where('manga', $manga)
                     ->where('source_id', $source_id)
                     ->orderBy('created_at', 'DESC')
                     ->first();
    }

    public function sendSubscribers($subscriptions, $notifier)
    {
        $channel_name = $this->getChannelName($notifier);

        foreach ($subscriptions as $subscription) {
            if ($channel_name == 'telegram') {
                $chat = $subscription->telegram_chat;
                $notifier->sendMessage($this->getMessage($channel_name, $chat));
            } elseif ($channel_name == 'messenger') {
                $chat = $subscription->messenger_chat;
                $notifier->sendGenericTemplate($chat->chat_id, $this->getMessage($channel_name));
            }
        }
    }

    private function getMessage($channel_name, $chat = null)
    {
        if ($channel_name == 'telegram') {
            return [
                'chat_id' => $chat->chat_id,
                'text' => $this->getTelegramMessage(),
                'parse_mode' => 'HTML'
            ];
        } elseif ($channel_name == 'messenger') {
            return [
                [
                    'title' => $this->manga. " " .$this->chapter,
                    'image_url' => 'https://s-media-cache-ak0.pinimg.com/originals/81/6c/79/816c79d251726a0de313011309281a74.png',
                    'buttons' => [
                        [
                            'type' => 'web_url',
                            'url' => $this->url,
                            'title' => $this->title
                        ]
                    ]
                ]
            ];
        }
    }

    private function getChannelName($notifier)
    {
        $class_name = get_class($notifier);

        if (strpos($class_name, 'Telegram') !== false) return 'telegram';
        if (strpos($class_name, 'Messenger') !== false) return 'messenger';

        return null;
    }

    private function getTelegramMessage()
    {
        return $this->manga . " <b>" . $this->chapter ."</b>\n" .
               "<i>" . $this->title . "</i> was released!\n".
               $this->url;
    }
}
