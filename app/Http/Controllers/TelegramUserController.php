<?php

namespace App\Http\Controllers;

use App\TelegramUser;
use App\MangaSource;

class TelegramUserController extends Controller
{
    public function index()
    {
        $telegram_users = TelegramUser::all();

        return view('admin.telegram_users.index', compact('telegram_users'));
    }

    public function subscriptions($id)
    {
        $telegram_user = TelegramUser::with('subscriptions','subscriptions.manga', 'subscriptions.source')->find($id);

        return view('admin.telegram_users.subscriptions', compact('telegram_user'));
    }
}
