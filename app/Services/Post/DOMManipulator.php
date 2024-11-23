<?php

namespace App\Services\Post;

use Closure;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class DOMManipulator
{
    public function processImages(string $html, Closure $imageProcessor): string
    {
        $crawler = new Crawler($html, useHtml5Parser: true);
        $images = $crawler->filter("img");

        $imageProcessor($images);

        $html = urldecode($this->getBufferedHtml($crawler));
        return $this->removeBodyTags($html);
    }

    /**
     * Get the buffered HTML content from the crawler.
     *
     * @param Crawler $crawler
     * @return string
     */
    private static function getBufferedHtml(Crawler $crawler): string
    {
        ob_start();
        echo $crawler->html();
        return ob_get_clean();
    }

    private function removeBodyTags(string $html): string
    {
        $html = Str::replaceFirst("<body>", "", $html);
        return Str::replaceFirst("</body>", "", $html);
    }
}
