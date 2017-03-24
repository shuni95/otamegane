<?php

namespace App\Services\Scrappers;

use App\Source;
use App\Notification;
use App\Subscription;

use Goutte;
use Telegram\Bot\Api as TelegramSender;
use App\Services\MessengerService as MessengerSender;

abstract class MangaScrapper
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

    protected $source_name;

    protected $is_recent;

    public function __construct()
    {
        $this->source = Source::where('name', $this->source_name)->first();
        $this->mangas = $this->source->mangas->pluck('name');
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
        return is_null(Notification::previous($manga, $chapter, $this->source->id)->first());
    }

    public function scrapping()
    {
        $crawler = Goutte::request('GET', $this->source->url);
        $is_recent = true;

        $this->filter($crawler)->each(function ($node) use (&$is_recent) {
            if ($is_recent) {
                $time = $this->getTime($node);

                if ($this->isRecent($time)) {
                    $manga = $this->identifyManga($node->html());

                    if (!is_null($manga)) {
                        $url     = $this->getUrl($node);
                        $title   = $this->getTitle($node);
                        $chapter = $this->getChapter($node);
                        if ($this->dontExistsPreviousNotification($manga, $chapter)) {
                            $text = $this->getTextNotification($manga, $chapter, $title, $time, $url);

                            $notification = Notification::create([
                                'manga'     => $manga,
                                'chapter'   => $chapter,
                                'title'     => $title,
                                'status'    => 'WIP',
                                'url'       => $url,
                                'source_id' => $this->source->id,
                            ]);

                            $notification->sendSubscribers(
                                $this->getTelegramSubscribers($manga),
                                new TelegramSender
                            );

                            $notification->sendSubscribers(
                                $this->getMessengerSubscribers($manga),
                                new MessengerSender
                            );

                            $notification->status = 'DONE';
                            $notification->save();
                        }
                    }
                } else {
                    $is_recent = false;
                }
            }
        });
    }

    protected function getTelegramSubscribers($manga)
    {
        return Subscription::ofTelegram($manga, $this->source->id)->get();
    }

    protected function getMessengerSubscribers($manga)
    {
        return Subscription::ofMessenger($manga, $this->source->id)->get();
    }

    protected function getTextNotification($manga, $chapter, $title, $time, $url)
    {
        return $manga . " <b>" . $chapter ."</b>\n" .
                "<i>" . $title . "</i> was released " . $time . "!\n".
                $url;
    }

    abstract protected function filter($crawler);

    abstract protected function getTime($node);
    abstract protected function getUrl($node);
    abstract protected function getTitle($node);
    abstract protected function getChapter($node);

    abstract protected function isRecent($time);
}
