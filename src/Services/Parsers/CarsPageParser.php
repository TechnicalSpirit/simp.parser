<?php

namespace SimpParser\Services\Parsers;

use InvalidArgumentException;
use SimpParser\Contracts\Interfaces\InterfaceParser;
use Symfony\Component\DomCrawler\Crawler;

class CarsPageParser implements InterfaceParser
{
    public function parse(string $html): array
    {
        $links = [];
        $crawler = new Crawler($html);
        try {
            $links = $crawler->filter('a.listing-image')->extract(['href']);
        }
        catch (InvalidArgumentException $exception)
        {
            return $links;
        }
        return $links;
    }
}