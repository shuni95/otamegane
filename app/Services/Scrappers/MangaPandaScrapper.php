<?php

namespace App\Services\Scrappers;

class MangaPandaScrapper extends MangaScrapper
{
    protected $source_id = 7;

    protected function filter($crawler)
    {
        return $crawler->filter('.updates > tr');
    }

    protected function getUrl($node)
    {
        $base_url = substr($node->getBaseHref(), 0, -1);
        if ($node->filter('.chaptersrec')->count() > 0) {
            return $base_url . $node->filter('.chaptersrec')->attr('href');
        } else {
            return $base_url . $node->filter('.chapter')->attr('href');
        }
    }

    protected function getTitle($node)
    {
        return $node->filter('.chapter strong')->text();
    }

    protected function getChapter($node)
    {
        if ($node->filter('.chaptersrec')->count() == 0) {
            return 'New';
        }

        $chapter = $node->filter('.chaptersrec')->text();

        return trim(substr($chapter, strlen($this->getTitle($node))));
    }

    protected function getTime($node)
    {
        $filter = $node->filter('.c4');

        if ($filter->count() == 0) {
            return 'Late';
        }

        return $filter->text();
    }

    protected function isRecent($time)
    {
        return ($time == 'Today');
    }

    protected function getTextNotification($manga, $chapter, $title, $time, $url)
    {
        return $manga . " <b>" . $chapter ."</b>\n" .
                "<i>" . $title . "</i> was released " . $time . "!\n".
                $url;
    }
}
