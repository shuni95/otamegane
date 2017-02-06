<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Goutte;
use Telegram;
use Spatie\Emoji\Emoji;
use App\TelegramUser;
use App\Notification;

use App\Services\Scrappers\MangaStreamScrapper;

class ScrappingManga extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrapper:manga';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapping a manga webpage';

    protected $mangas;

    protected $is_recent;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $scrapper = (new MangaStreamScrapper)->scrapping();
    }
}
