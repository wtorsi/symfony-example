<?php

declare(strict_types=1);

namespace Location\File\Processor;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Location\Entity\City;
use Location\Entity\Country;
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
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
    }

    public function process(GeoIpDto $dto): void
    {
        $this->doProcess($dto);
        $this->em->clear();
    }

    private function doProcess(GeoIpDto $dto): void
    {
        if (!$dto->getCityName()) {
            return;
        }

        if ($this->isCityExist($dto)) {
            $this->em->clear();

            return;
        }

        $subdivision = $this->getSubdivision($dto);
        $country = $this->getCountry($dto);
        $entity = new City($country, $subdivision, $dto->getId(), $dto->getCityName(), $dto->getTimeZone());
        $this->em->persist($entity);
        $this->em->flush();
        $this->em->clear();
    }

    private function getSubdivision(GeoIpDto $dto): Subdivision
    {
        /** @var EntityRepository $er */
        $er = $this->em->getRepository(Subdivision::class);
        $entity = $er->findOneBy(['isoCode' => $dto->getSubdivisionIsoCode()]);
        if (null === $entity) {
            $entity = new Subdivision($dto->getSubdivisionIsoName(), $dto->getSubdivisionIsoCode());
            $this->em->persist($entity);
        }

        return $entity;
    }

    private function getCountry(GeoIpDto $dto): Country
    {
        /** @var EntityRepository $er */
        $er = $this->em->getRepository(Country::class);
        $entity = $er->findOneBy(['isoCode' => $dto->getCountryIsoCode()]);
        if (null === $entity) {
            $entity = new Country($dto->getCountryIsoCode(), $dto->getCountryName());
            $this->em->persist($entity);
        }

        return $entity;
    }

    private function isCityExist(GeoIpDto $dto): bool
    {
        return (bool) $this->em->getRepository(City::class)->findOneBy(['externalId' => $dto->getId()]);
    }
}
