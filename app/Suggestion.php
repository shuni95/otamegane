<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    protected $fillable = ['name', 'source_id', 'telegram_chat_id'];

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function telegram_chat()
    {
        return $this->belongsTo(TelegramChat::class);
    }
}
