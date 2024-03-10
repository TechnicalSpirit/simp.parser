<?php

namespace SimpParser\Command;

use SimpParser\Services\Logger\Log;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use SimpParser\Services\Builders\CSVBuilder;
use SimpParser\Services\Parsers\CarsPageParser;
use SimpParser\Services\Parsers\CarParser;
use SimpParser\Services\Readers\SiteReader;
use SimpParser\SmartSites;

class ParseDataCommand extends Command
{
    protected static $defaultName = "start_parse";

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pathToSave = $input->getOption('path_to_save');
        $url = $input->getOption('url');

        if( ! is_dir( dirname($pathToSave) ) )
            throw new \InvalidArgumentException("There is no path to the directory");

        if( ! filter_var($url, FILTER_VALIDATE_URL) )
            throw new \InvalidArgumentException("not valida url");

        try {

            $smartSites = new SmartSites(
                new SiteReader(),
                new CarsPageParser(),
                new CarParser(),
                new CSVBuilder()
            );

            $smartSites->setBaseURL($url);
            $smartSites->downloadData($pathToSave);
        }
        catch (\Exception $exception) {

            Log::error($exception->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('this command parses cats data from site https://premiumcarsfl.com')
            ->setHelp('you can add the --path_to_save="path" argument to change where the car data is saved')
            ->addOption('path_to_save',
                null,
                InputOption::VALUE_OPTIONAL,
                'path to save the file',
            "data.csv")

            ->addOption('url',
                null,
                InputOption::VALUE_OPTIONAL,
                'url to page with cars',
            "https://premiumcarsfl.com/listing-list-full/")
        ;
    }

}