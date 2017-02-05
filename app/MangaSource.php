<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MangaSource extends Model
{
    protected $table = 'manga_source';

    public function subscribers()
    {
        return $this->belongsToMany(TelegramUser::class, 'subscriptions');
    }

    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }
}
