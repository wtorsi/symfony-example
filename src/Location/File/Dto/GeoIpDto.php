<?php declare(strict_types=1);

namespace Location\File\Dto;

class GeoIpDto
{
    private string $countryIsoCode;
    private string $subdivisionIsoCode;
    private string $subdivisionIsoName;
    private string $cityName;
    private string $timeZone;
    private string $countryName;
    private int $id;

    public function __construct(int $id, string $countryIsoCode, string $countryName, string $subdivisionIsoCode, string $subdivisionIsoName, string $cityName, string $timeZone)
    {
        $this->id = $id;
        $this->countryIsoCode = $countryIsoCode;
        $this->subdivisionIsoCode = $subdivisionIsoCode;
        $this->subdivisionIsoName = $subdivisionIsoName;
        $this->cityName = $cityName;
        $this->timeZone = $timeZone;
        $this->countryName = $countryName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCountryIsoCode(): string
    {
        return $this->countryIsoCode;
    }

    public function getSubdivisionIsoCode(): string
    {
        return $this->subdivisionIsoCode;
    }

    public function getSubdivisionIsoName(): string
    {
        return $this->subdivisionIsoName;
    }

    public function getCityName(): string
    {
        return $this->cityName;
    }

    public function getTimeZone(): string
    {
        return $this->timeZone;
    }

    /**
     * @return string
     */
    public function getCountryName(): string
    {
        return $this->countryName;
    }
}