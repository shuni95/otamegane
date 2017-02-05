<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\TelegramUser;

class TelegramUserTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test create a telegram user and see in the database
     */
    public function test_create_telegram_user()
    {
        TelegramUser::create(['user_id' => env('TELEGRAM_TEST_USER_ID', 123456789), 'first_name' => 'Junior', 'last_name' => 'Zavaleta', 'username' => 'JuniorZavaleta']);

        $this->assertTrue(TelegramUser::all()->count() == 1);

        $this->assertDatabaseHas('telegram_users' ,[
            'username' => 'JuniorZavaleta'
        ]);
    }
}
