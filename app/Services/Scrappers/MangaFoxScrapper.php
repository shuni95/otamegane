<?php

namespace App\Services\Scrappers;

use Notification;
use Telegram;

class MangaFoxScrapper extends MangaScrapper
{
    protected $source_id = 6;

    protected function filter($crawler)
    {
        return $crawler->filter('#updates > li');
    }

    protected function getUrl($node)
    {
        return $node->filter('.series_preview')->attr('href');
    }

    protected function getTitle($node)
    {
        return $node->filter('.series_preview')->text();
    }

    protected function getChapter($node)
    {
        $chapter = $node->filter('a.chapter')->text();

        return trim(substr($chapter, strlen($this->getTitle($node))));
    }

    protected function getTime($node)
    {
        return $node->filter('span')->text();
    }

    protected function isRecent($time)
    {
        return (($time == 'Today') || strpos($time, 'minute') || strpos($time, 'hour'));
    }

    protected function getTextNotification($manga, $chapter, $title, $time, $url)
    {
        return $manga . " <b>" . $chapter ."</b>\n" .
                "<i>" . $title . "</i> was released " . $time . "!\n".
                $url;
    }
}
