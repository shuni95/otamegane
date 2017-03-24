<?php

namespace App\Services\Scrappers;

class MangaStreamScrapper extends MangaScrapper
{
    protected $source_name = 'MangaStream';

    protected function filter($crawler)
    {
        return $crawler->filter('.new-list > li');
    }

    protected function getUrl($node)
    {
        return $node->filter('a')->attr('href');
    }

    protected function getTitle($node)
    {
        return $node->filter('em')->text();
    }

    protected function getChapter($node)
    {
        return $node->filter('strong')->text();
    }

    protected function getTime($node)
    {
        return $node->filter('span')->text();
    }

    protected function isRecent($time)
    {
        $month = substr($time, 0, 3);

        if ($time == 'Today') return true;

        if (strpos($time, 'day ago')) return false;

        if (strpos($time, 'days ago')) return false;

        if (in_array($month, ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'])) return false;

        return true;
    }

    /**
     * Identify the manga's name
     * @param  string $html
     * @return string
     */
    protected function identifyManga($html)
    {
        return $this->mangas->first(function ($manga) use ($html) {
            $start = strpos($html, "</i>") + 4;
            $end = strpos($html, "<strong>") - $start;
            $html_manga = substr($html, $start, $end);

            return trim($html_manga) == $manga;
        });
    }
}
