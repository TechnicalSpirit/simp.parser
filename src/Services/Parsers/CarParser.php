<?php

namespace SimpParser\Services\Parsers;

use InvalidArgumentException;
use SimpParser\Contracts\Interfaces\InterfaceParser;
use Symfony\Component\DomCrawler\Crawler;

class CarParser implements InterfaceParser
{
    public function parse(string $html): array
    {
        $crawler = new Crawler($html);
        $data = [];

        $this->parseValueFromDetailBox($crawler,$data );
        $this->parseSrcFromBigImg($crawler,$data );
        $this->parsePrice($crawler,$data );

        return $data;
    }

    private function parseValueFromDetailBox(Crawler $crawler, array & $data):void
    {
        $links = $crawler->filter('ul.list li');

        $links->each(function (Crawler $node, $i) use (&$data) {

            $key = rtrim( $node->filter('.text')->text(), ":");

            try{
                $value = $node->filter('a.listing-tax')->text();
            }
            catch (InvalidArgumentException $exception) {
                $value = $node->filter('.value')->text();
            }

            $data[$key] = $value;
        });
    }

    private function parseSrcFromBigImg(Crawler $crawler, array & $data):void
    {
        try{
            $image = $crawler->filter('img.attachment-voiture-gallery-large.size-voiture-gallery-large')
                ->first()
                ->attr('src');
        }
        catch (InvalidArgumentException $exception) {
            $image = "";
        }

        $data["image_link"] = $image;
    }

    private function parsePrice(Crawler $crawler, array & $data):void
    {
        try{
            $price = $crawler->filter('.flex-middle-sm.header-detail-bottom .listing-action-detail.ali-right .listing-price .price-text')
                ->first()
                ->text();
        }
        catch (InvalidArgumentException $exception) {
            $price = "";
        }

        $data["Price"] = $price;
    }
}