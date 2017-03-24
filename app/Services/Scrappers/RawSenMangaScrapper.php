<?php

namespace App\Services\Scrappers;

class RawSenMangaScrapper extends MangaScrapper
{
    protected $source_name = 'RawSenManga';

    protected function filter($crawler)
    {
        return $crawler->filter('.info tr.odd, .info tr.even');
    }

    protected function getUrl($node)
    {
        $base_url = substr($node->getBaseHref(), 0, -1);

        return $base_url . $node->filter('a')->attr('href');
    }

    protected function getTitle($node)
    {
        $row = $node->filter('a')->text();
        $end = strpos($row, ' Chapter ');

        return trim(substr($row, 0, $end));
    }

    protected function getChapter($node)
    {
        $row   = $node->filter('a')->text();
        $start = strpos($row, ' Chapter ');
        $end   = strpos($row, ' - Raw');

        return trim(substr($row, $start, $end - $start));
    }

    protected function getTime($node)
    {
        return $node->filter('td')->last()->text();
    }

    protected function isRecent($time)
    {
        return (strpos($time, 'minuto') || strpos($time, 'hora'));
    }
}
