<?php

namespace Location\Detector;

use Location\Bridge\GeoIpBridge;
use Location\Entity\City;
use Symfony\Component\HttpFoundation\Request;

class CityDetector implements CityDetectorInterface
{
    private GeoIpBridge $bridge;

    public function __construct(GeoIpBridge $bridge)
    {
        $this->bridge = $bridge;
    }

    public function detect(Request $request): ?City
    {
        return $this->bridge->findCityByIp($request->getClientIp());
    }
}
