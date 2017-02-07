<?php

namespace App\Http\Controllers;

use App\TelegramChat;
use App\MangaSource;

class TelegramChatController extends Controller
{
    public function index()
    {
        $telegram_users = TelegramChat::all();

        return view('admin.telegram_users.index', compact('telegram_users'));
    }

    public function subscriptions($id)
    {
        $telegram_user = TelegramChat::with('subscriptions','subscriptions.manga', 'subscriptions.source')->find($id);

        return view('admin.telegram_users.subscriptions', compact('telegram_user'));
    }
}
