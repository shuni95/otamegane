<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model
{
    protected $fillable = ['user_id', 'first_name', 'last_name', 'username'];

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
}
