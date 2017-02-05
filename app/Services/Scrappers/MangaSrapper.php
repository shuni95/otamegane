<?php

namespace App\Services\Scrappers;

use App\Source;
use App\Notification;
use Goutte;
use Telegram;

abstract class MangaSrapper
{
    /**
     * Collection of manga, each page have a set of manga
     * @var Collection
     */
    protected $mangas;

    /**
     * Must define in each implementation, id of the source in DB
     * @var integer
     */
    protected $source;

    protected $source_id;

    protected $is_recent;

    public function __construct()
    {
        $this->source    = Source::find($this->source_id);
        $this->mangas    = $this->source->mangas->pluck('name');
    }

    /**
     * Identify the manga's name
     * @param  string $html
     * @return string
     */
    protected function identifyManga($html)
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
    protected function dontExistsPreviousNotification($manga, $chapter)
    {
        return Notification::dontExistsPrevious($manga, $chapter, $this->source_id);
    }

    public function scrapping()
    {
        $crawler = Goutte::request('GET', $this->source->url);
        $is_recent = true;

        $this->filter($crawler)->each(function ($node) use (&$is_recent) {
            if ($is_recent) {
                $manga   = $this->identifyManga($node->html());

                if (!is_null($manga)) {
                    $time = $this->getTime($node);

                    if ($this->isRecent($time)) {
                        $url     = $this->getUrl($node);
                        $title   = $this->getTitle($node);
                        $chapter = $this->getChapter($node);

                        if ($this->dontExistsPreviousNotification($manga, $chapter)) {
                            $text = $this->getTextNotification($manga, $chapter, $title, $time, $url);

                            $notification = Notification::create([
                                'manga' => $manga,
                                'chapter' => $chapter,
                                'title' => $title,
                                'status' => 'WIP',
                                'source_id' => $this->source_id,
                            ]);

                            $this->getSubscribers()->each(function ($suscriber) use ($text) {
                                Telegram::sendMessage([
                                    'chat_id' => $suscriber,
                                    'text' => $text,
                                    'parse_mode' => 'HTML'
                                ]);
                            });

                            $notification->status = 'DONE';
                            $notification->save();
                        }

                    } else {
                        $is_recent = false;
                    }
                }
            }
        });
    }

    protected function getSubscribers()
    {
        // return Manga::subscribersOf($source_id)->get();
        return collect([env('TELEGRAM_TEST_USER_ID')]);
    }

    abstract protected function filter($crawler);

    abstract protected function getTime($node);
    abstract protected function getUrl($node);
    abstract protected function getTitle($node);
    abstract protected function getChapter($node);

    abstract protected function isRecent($time);

    abstract protected function getTextNotification($manga, $chapter, $title, $time, $url);
}