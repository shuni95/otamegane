<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessengerChat extends Model
{
    protected $fillable = ['chat_id', 'first_name', 'last_name', 'locale', 'gender'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'messenger_chat_id', 'chat_id');
    }
}
