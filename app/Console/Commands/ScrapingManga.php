<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Goutte;
use Telegram;
use Spatie\Emoji\Emoji;
use App\TelegramUser;
use App\Notification;

class ScrapingManga extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:manga';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scraping a manga webpage';

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

        $this->mangas = collect([
            'Fairy Tail',
            'One Piece',
            'Haikyu',
        ]);
        $this->is_recent = true;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $crawler = Goutte::request('GET', 'http://mangastream.com/');

        $crawler->filter('.new-list > li')->each(function ($node) {
            if ($this->is_recent) {
                $manga   = $this->identifyManga($node->html());

                if (!is_null($manga)) {
                    $time    = $node->filter('span')->text();

                    if (strpos($time, 'days ago') === false) {
                        $url     = $node->filter('a')->attr('href');
                        $title   = $node->filter('em')->text();
                        $chapter = $node->filter('strong')->text();

                        if ($this->dontExistsPreviousNotification($manga, $chapter)) {
                            $this->notify($manga, $chapter, $title, $time, $url);
                        } else {
                            $this->info($manga . ' ' . $chapter . ' has already notified.');
                        }

                    } else {
                        $this->is_recent = false;
                    }
                }
            } else {
                $this->is_recent = false;
            }
        });
    }

    /**
     * Identify the manga's name
     * @param  string $html
     * @return string
     */
    private function identifyManga($html)
    {
        return $this->mangas->first(function ($manga) use ($html) {
            return strpos($html, $manga) !== false;
        });
    }

    /**
     * Verify if not exists a previous notification
     * @param  string $manga
     * @param  string $chapter
     * @return bool
     */
    private function dontExistsPreviousNotification($manga, $chapter)
    {
        return is_null(Notification::where('manga', $manga)->where('chapter', $chapter)->first());
    }

    /**
     * Notify all users suscribed
     * @param  string $manga
     * @param  string $chapter
     * @param  string $title
     * @param  string $time
     * @param  string $url
     * @return void
     */
    private function notify($manga, $chapter, $title, $time, $url)
    {
        $notification = Notification::create([
            'manga' => $manga,
            'chapter' => $chapter,
            'title' => $title,
            'status' => 'WIP',
        ]);

        // Notify all the telegram users suscribed to the manga
        Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_TEST_USER_ID'),
            'text' => $manga . " <b>" . $chapter ."</b>\n" .
                "<i>" . $title . "</i> was released " . $time . "!\n".
                $url,
            'parse_mode' => 'HTML'
        ]);

        $notification->status = 'DONE';
        $notification->save();
    }
}
