<?php

namespace App\Services\Scrappers;

class LeoMangaScrapper extends MangaScrapper
{
    protected $source_name = 'LeoManga';

    protected function filter($crawler)
    {
        return $crawler->filter('.col-sm-8.col8-md > ul.list-unstyled > li');
    }

    protected function getUrl($node)
    {
        $base_url = substr($node->getBaseHref(), 0, -1);

        return $base_url . $node->filter('.cap-upd >a')->attr('href');
    }

    protected function getTitle($node)
    {
        return $node->filter('a')->text();
    }

    protected function getChapter($node)
    {
        $chapter = $node->filter('.cap-upd > a')->text();

        return trim(substr($chapter, strlen($this->getTitle($node))));
    }

    protected function getTime($node)
    {
        return $node->filter('.manga-time-update')->text();
    }

    protected function isRecent($time)
    {
        return (strpos($time, 'minuto') || strpos($time, 'hora'));
    }

    protected function getTextNotification($manga, $chapter, $title, $time, $url)
    {
        return $manga . " <b>" . $chapter ."</b>\n" .
                "<i>" . $title . "</i> was released " . $time . "!\n".
                $url;
    }
}
