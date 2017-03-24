<?php

namespace App\Http\Controllers;

use App\TelegramChat;
use App\MangaSource;

class TelegramChatController extends Controller
{
    public function index()
    {
        $telegram_chats = TelegramChat::all();

        return view('admin.telegram_chats.index', compact('telegram_chats'));
    }

    public function subscriptions($id)
    {
        $telegram_chat = TelegramChat::with(
            'subscriptions'
        )
        ->where('chat_id', $id)
        ->first();

        return view('admin.telegram_chats.subscriptions', compact('telegram_chat'));
    }
}
