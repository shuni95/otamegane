<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TelegramChat extends Model
{
    protected $primaryKey = 'chat_id';

    protected $fillable = ['chat_id', 'first_name', 'last_name', 'username', 'title', 'type'];

    public function subscriptions()
    {
        return $this->belongsToMany(MangaSource::class, 'subscriptions');
    }

    public function scopeSubscribedTo($query, $manga, $source_id)
    {
        $query->whereHas('subscriptions', function ($subscription) use ($manga, $source_id) {
            $subscription->whereHas('manga', function ($manga_query) use ($manga) {
                $manga_query->where('name', $manga);
            })
            ->where('source_id', $source_id);
        });
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }
}
