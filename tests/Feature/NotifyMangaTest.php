<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Telegram\Bot\Api;
use Mockery;
use App\Subscription;
use App\Notification;
use App\MangaSource;
use App\Services\MessengerService;

class NotifyMangaTest extends TestCase
{
    use DatabaseMigrations;

    public function test_notify_manga_to_telegram_chat_subscribed()
    {
        $manga_source  = factory(MangaSource::class)->create();
        $subscriptions = factory(Subscription::class, 5)->states('telegram')->create();

        $telegram = Mockery::spy(Api::class);

        $notification  = factory(Notification::class)->create();

        $notification->sendSubscribers($subscriptions, $telegram);
        $telegram->shouldHaveReceived('sendMessage')->times(5);
    }

    public function test_notify_manga_to_messenger_chat_subscribed()
    {
        $manga_source  = factory(MangaSource::class)->create();
        $subscriptions = factory(Subscription::class, 4)->states('messenger')->create();

        $messenger = Mockery::spy(MessengerService::class);

        $notification = factory(Notification::class)->create();

        $notification->sendSubscribers($subscriptions, $messenger);
        $messenger->shouldHaveReceived('sendGenericTemplate')->times(4);
    }
}
