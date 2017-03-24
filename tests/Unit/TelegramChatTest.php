<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\TelegramChat;

class TelegramChatTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test create a telegram user and see in the database
     */
    public function test_create_telegram_user()
    {
        TelegramChat::create(['chat_id' => env('TELEGRAM_TEST_USER_ID', 123456789), 'first_name' => 'Junior', 'last_name' => 'Zavaleta', 'username' => 'JuniorZavaleta', 'type' => 'private']);

        $this->assertTrue(TelegramChat::all()->count() == 1);

        $this->assertDatabaseHas('telegram_chats' ,[
            'username' => 'JuniorZavaleta'
        ]);
    }
}
