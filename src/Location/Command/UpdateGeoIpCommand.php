<?php

namespace Location\Command;

use Location\File\Processor\GeoIpProcessor;
use Location\File\Reader\GeoIpCsvReader;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateGeoIpCommand extends \AbstractCommand
{
    public static $defaultName = 'location:database:update';
    private string $path;
    /**
     * @var GeoIpProcessor
     */
    private GeoIpProcessor $processor;

    public function __construct(GeoIpProcessor $processor, string $path = './var/geoip/GeoLite2-City-Locations-ru.csv')
    {
        $this->path = $path;
        $this->processor = $processor;
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('path', InputArgument::OPTIONAL, 'Path to *.csv files. Please refer https://dev.maxmind.com/geoip/geoip2/geolite2/ for more details.', $this->path);
        $this->addArgument('locale', InputArgument::OPTIONAL, 'Locale to set while update.', 'ru');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');
        $reader = new GeoIpCsvReader($path);
        try {
            while ($row = $reader->read()) {
                $this->processor->process($row);
            }
        } catch (\Throwable $e) {
            $this->io->warning($e->getMessage());
            throw $e;
        }

        $this->io->success('DB was updated successfully');

        return 0;
    }
}
