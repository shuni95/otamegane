<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manga extends Model
{
    protected $fillable = ['name'];

    public function sources()
    {
        return $this->belongsToMany(Source::class);
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

    public function getNumSourcesAttribute()
    {
        return $this->sources->count();
    }
}
