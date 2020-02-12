<?php declare(strict_types=1);

namespace Location\Reader;

class CsvReader
{
    public function read(array $row)
    {
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

        //only russian
        if ('RU' != \strtoupper($countryIsoCode)) {
            return null;
        }
    }
}