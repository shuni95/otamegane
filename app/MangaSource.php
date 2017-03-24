<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MangaSource extends Model
{
    protected $table = 'manga_source';

    public $timestamps = false;

    public function subscribers()
    {
        return $this->belongsToMany(TelegramChat::class, 'subscriptions', 'manga_source_id', 'telegram_chat_id');
    }

    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public static function getMangaInSource($manga_name, $source_name)
    {
        return (new static())::whereHas('manga', function ($manga) use ($manga_name) {
            $manga->where('name', $manga_name);
        })->whereHas('source', function ($source) use ($source_name) {
            $source->where('name', $source_name);
        })->first();
    }
}
