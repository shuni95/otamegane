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
            }

            $notifier->sendMessage($this->getMessage($channel_name, $chat));
        }
    }

    private function getMessage($channel_name, $chat)
    {
        if ($channel_name == 'telegram') {
            return [
                'chat_id' => $chat->chat_id,
                'text' => $this->getTelegramMessage(),
                'parse_mode' => 'HTML'
            ];
        } elseif ($channel_name == 'messenger') {
            // To-do
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
