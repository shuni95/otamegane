<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model
{
    protected $fillable = ['user_id', 'first_name', 'last_name', 'username'];
}
