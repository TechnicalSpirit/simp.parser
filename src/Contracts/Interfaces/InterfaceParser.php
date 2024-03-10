<?php

namespace SimpParser\Contracts\Interfaces;

interface InterfaceParser
{
    public function parse(string $html):array;
}