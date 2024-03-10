<?php

namespace SimpParser;

use SimpParser\Contracts\Interfaces\InterfaceDataReader;
use SimpParser\Contracts\Interfaces\InterfaceFileBuilder;
use SimpParser\Contracts\Interfaces\InterfaceParser;
use SimpParser\Services\Logger\Log;

class SmartSites
{
    private string $baseURL;
    private InterfaceDataReader $reader;
    private InterfaceParser $carsPageParser;
    private InterfaceParser $carParser;
    private InterfaceFileBuilder $builder;

    public function __construct(InterfaceDataReader  $reader,
                                InterfaceParser      $carsPageParser,
                                InterfaceParser      $carParser,
                                InterfaceFileBuilder $builder)
    {
        $this->reader = $reader;
        $this->carsPageParser = $carsPageParser;
        $this->carParser = $carParser;
        $this->builder = $builder;
    }

    public function setBaseURL(string $baseURL) : void
    {
        $this->baseURL = $baseURL;
    }

    public function downloadData(string $path): void
    {
        Log::info("receiving data");

        $allCarsLinks = $this->getAllCarPagesLinks();

        $carsParsData = [];
        foreach ($allCarsLinks as $link)
        {
            $carPage = $this->reader->readData($link);
            $parserData = $this->carParser->parse($carPage);

            $carData = $this->getNecessaryData($link, $parserData);

            $carsParsData[] = $carData;

            Log::info("processed: $link");
        }
        $this->builder->buildFile($carsParsData, $path);
    }

    private function getNecessaryData(string $link, array $parserData): array
    {
        $storeCode = $_ENV['STORE_CODE'];
        $carImgSrs = $parserData["image_link"];

        return [
            "Condition" => $_ENV['CONDITION'],
            "google_product_category" => $_ENV['GOOGLE_PRODUCT_CATEGORY'],
            "store_code" => $storeCode,
            "vehicle_fulfillment(option:store_code)" => $_ENV['VEHICLE_FULFILLMENT_OPTION_STORE_CODE'],
            "Brand" => $parserData["Make"],
            "Model" => $parserData["Model"],
            "Year" => $parserData["Year"],
            "Color" => $parserData["Color"],
            "Mileage" => $parserData["Mileage"]. " miles",
            "Price" => $parserData["Price"],
            "VIN" => $parserData["VIN"],
            "image_link" => $carImgSrs,
            "link_template" => "$link?store=$storeCode"
        ];
    }
    private function getAllCarPagesLinks():array
    {
        $page = 1;
        $allLinks = [];
        while (true)
        {
            $pageHtml = $this->reader->readData("$this->baseURL/page/$page/");
            $linksOnePage = $this->carsPageParser->parse($pageHtml);

            if(!$this->isLinksStillLeft($linksOnePage))
                break;

            $allLinks = array_merge($allLinks,$linksOnePage);
            $page++;
        }
        return $allLinks;
    }

    private function isLinksStillLeft(array $linksOnePage):bool
    {
        return count($linksOnePage) !== 0;
    }
}