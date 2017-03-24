<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'manga_source_id',
        'telegram_chat_id',
        'messenger_chat_id'
    ];

    public static function alreadySubscribed($manga_source_id, $chat_id)
    {
        $telegram_subscription = (new static())::where('manga_source_id', $manga_source_id)
            ->where('telegram_chat_id', $chat_id)
            ->first();

        $messenger_subscription = (new static())::where('manga_source_id', $manga_source_id)
            ->where('messenger_chat_id', $chat_id)
            ->first();

        return !is_null($telegram_subscription) || !is_null($messenger_subscription);
    }

    public function scopeOfTelegram($query, $manga, $source_id)
    {
        return $query->of($manga, $source_id)->whereNotNull('telegram_chat_id');
    }

    public function scopeOfMessenger($query, $manga, $source_id)
    {
        return $query->of($manga, $source_id)->whereNotNull('messenger_chat_id');
    }

    public function scopeOf($query, $manga, $source_id)
    {
        return $query->whereHas(
            'manga_source.manga', function ($manga_query) use ($manga) {
                $manga_query->where('name', $manga);
            }
        )->whereHas(
            'manga_source', function ($q) use ($source_id) {
                $q->where('source_id', $source_id);
            }
        );
    }

    public function telegram_chat()
    {
        return $this->belongsTo(TelegramChat::class, 'telegram_chat_id', 'chat_id');
    }

    public function messenger_chat()
    {
        return $this->belongsTo(MessengerChat::class, 'messenger_chat_id', 'chat_id');
    }

    public function manga_source()
    {
        return $this->belongsTo(MangaSource::class);
    }

    public function getMangaAttribute()
    {
        return $this->manga_source->manga;
    }

    public function getSourceAttribute()
    {
        return $this->manga_source->source;
    }
}
