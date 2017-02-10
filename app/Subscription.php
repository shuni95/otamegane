<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = ['manga_source_id', 'telegram_chat_id'];

    public static function alreadySubscribed($manga_source_id, $chat_id)
    {
        $subscription = (new static())::where('manga_source_id', $manga_source->id)
            ->where('telegram_chat_id', $chat_id)
            ->first();

        return !is_null($subscription);
    }
}
