<?php

namespace Location\Detector;

use Doctrine\ORM\EntityManagerInterface;
use Location\Bridge\GeoIpBridge;
use Location\Entity\City;
use Symfony\Component\HttpFoundation\Request;

class CityDetector implements CityDetectorInterface
{
    private EntityManagerInterface $em;
    private GeoIpBridge $bridge;

    public function __construct(EntityManagerInterface $em, GeoIpBridge $bridge)
    {
        $this->em = $em;
        $this->bridge = $bridge;
    }

    public function detect(Request $request): ?City
    {
        $city = $this->bridge->findCityByIp($request->getClientIp());
        if (null !== $city) {
            return $city;
        }

        return null;
    }
}
