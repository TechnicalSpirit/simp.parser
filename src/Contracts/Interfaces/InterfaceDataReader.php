<?php

namespace SimpParser\Contracts\Interfaces;

interface InterfaceDataReader
{
    public function readData(string $path): string;

}