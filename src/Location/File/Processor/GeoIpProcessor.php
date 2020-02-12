<?php

declare(strict_types=1);

namespace Location\File\Processor;

use Doctrine\ORM\EntityManagerInterface;
use Location\Entity\City;
use Location\Entity\Subdivision;
use Location\File\Dto\GeoIpDto;

class GeoIpProcessor
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function process(GeoIpDto $dto): void
    {
        if (!$dto->getCityName() || 'RU' !== $dto->getCountryIsoCode()) {
            return;
        }

        $subdivision = $this->getSubdivision($dto);
        $entity = $this->tryFindCity($subdivision, $dto);
        if (null !== $entity) {
            return;
        }

        $entity = new City($subdivision, $dto->getCityName(), $dto->getTimeZone());
        $this->em->persist($entity);
        $this->em->flush();
        $this->em->clear();
    }

    private function getSubdivision(GeoIpDto $dto): Subdivision
    {
        $er = $this->em->getRepository(Subdivision::class);
        $entity = $er->findOneBy(['isoCode' => $dto->getSubdivisionIsoCode()]);
        if (null === $entity) {
            $entity = new Subdivision($dto->getSubdivisionIsoName(), $dto->getSubdivisionIsoCode());
            $this->em->persist($entity);
        }

        return $entity;
    }

    private function tryFindCity(Subdivision $subdivision, GeoIpDto $dto): ?City
    {
        $er = $this->em->getRepository(City::class);

        return $er->findOneBy([
            'subdivision' => $subdivision,
            'name' => $dto->getCityName(),
        ]);
    }
}
