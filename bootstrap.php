<?php

use Dotenv\Dotenv;
use SimpParser\Services\Logger\Log;

require_once "vendor/autoload.php";

$dotenv = Dotenv::createImmutable(__DIR__, 'config');
$dotenv->load();

Log::init(__DIR__);