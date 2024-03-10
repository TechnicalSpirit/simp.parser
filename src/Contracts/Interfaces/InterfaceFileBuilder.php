<?php

namespace SimpParser\Contracts\Interfaces;

interface InterfaceFileBuilder
{
    public function buildFile(array $data, string $fileName):void;
}