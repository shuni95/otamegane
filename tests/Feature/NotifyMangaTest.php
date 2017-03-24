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

class NotifyMangaTest extends TestCase
{
    use DatabaseMigrations;

    public function test_notify_manga_to_telegram_chat_subscribed()
    {
        $manga_source  = factory(MangaSource::class)->create();
        $subscriptions = factory(Subscription::class, 5)->states('telegram')->create();

        $telegram = Mockery::spy(Api::class);

        $notification  = Notification::create([
            'manga' => 'test',
            'chapter' => 'test',
            'title' => 'test',
            'status' => 'test',
            'source_id' => '1',
            'url' => 'test.example'
        ]);

        $notification->sendSubscribers($subscriptions, $telegram, 'telegram');
        $telegram->shouldHaveReceived('sendMessage')->times(5);
    }
}
