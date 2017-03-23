<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessengerChat extends Model
{
    protected $primaryKey = 'chat_id';

    protected $fillable = ['chat_id', 'first_name', 'last_name', 'locale', 'gender'];
}
