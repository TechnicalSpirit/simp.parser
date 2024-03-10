<?php

use SimpParser\Command\ParseDataCommand;
use SimpParser\Services\Logger\Log;
use Symfony\Component\Console\Application;

require_once "bootstrap.php";

$application = new Application();

$application->add(new ParseDataCommand());

$application->run();


