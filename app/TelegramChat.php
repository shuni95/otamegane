<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TelegramChat extends Model
{
    protected $fillable = ['chat_id', 'first_name', 'last_name', 'username', 'title', 'type'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'telegram_chat_id', 'chat_id');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }
}
