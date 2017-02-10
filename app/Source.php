<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $fillable = ['url', 'name'];

    public function mangas()
    {
        return $this->belongsToMany(Manga::class);
    }

    public function manga_sources()
    {
        return $this->hasMany(MangaSource::class);
    }

    public function subscriptions()
    {
        return $this->hasManyThrough(Subscription::class, MangaSource::class);
    }

    public function getTotalSubscribersAttribute()
    {
        return $this->subscriptions->unique('telegram_chat_id')->count();
    }

    public function getTotalSubscriptionsAttribute()
    {
        return $this->subscriptions->count();
    }

    public function getNumMangasAttribute()
    {
        return $this->mangas->count();
    }
}
