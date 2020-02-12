<?php

declare(strict_types=1);

namespace Location\File\Reader;

use Location\File\Dto\GeoIpDto;

class GeoIpCsvReader extends \SplFileObject
{
    public function __construct(string $path)
    {
        parent::__construct($path);
        $this->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::READ_AHEAD | \SplFileObject::DROP_NEW_LINE);
        $this->setCsvControl(',', '"', '\\');
        //skip first line
        $this->seek(1);
    }

    public function read(): ?GeoIpDto
    {
        $row = $this->fgetcsv();
        if (null === $row) {
            return null;
        }

        [$geoNameId,
         $localeCode,
         $continentCode,
         $continentName,
         $countryIsoCode,
         $countryName,
         $subdivision1IsoCode,
         $subdivision1Name,
         $subdivision2IsoCode,
         $subdivision2Name,
         $cityName,
         $metroCode,
         $timeZone] = $row;

        return new GeoIpDto($countryIsoCode, $subdivision1IsoCode, $subdivision1Name, $cityName, $timeZone);
    }
}
