<?php

namespace SimpParser\Services\Readers;

use GuzzleHttp\Client;
use SimpParser\Contracts\Interfaces\InterfaceDataReader;

class SiteReader implements InterfaceDataReader
{

    public function readData(string $path): string
    {
        $client = new Client();
        $res = $client->request('GET', $path);
        return $res->getBody();
    }

}