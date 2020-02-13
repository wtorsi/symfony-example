<?php

declare(strict_types=1);

namespace Location\Bridge;

use Doctrine\ORM\EntityManagerInterface;
use GeoIp2\Database\Reader;
use Location\Entity\City;

class GeoIpBridge
{
    private EntityManagerInterface $em;
    private string $path;

    public function __construct(EntityManagerInterface $em, string $path)
    {
        $this->em = $em;
        $this->path = $path;
    }

    public function findCityByIp(string $ip): ?City
    {
        try {
            $reader = new Reader($this->path);
            $record = $reader->city($ip);
        } catch (\Throwable $e) {
            return null;
        }

        if (!$id = $record->city->geonameId) {
            return null;
        }

        return $this->em->getRepository(City::class)->findOneBy(['externalId' => $id]);
    }
}
