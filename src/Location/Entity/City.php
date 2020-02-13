<?php

namespace Location\Entity;

use Core\Entity\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\ChangeTrackingPolicy(value="DEFERRED_EXPLICIT")
 * @ORM\Cache(usage="READ_ONLY")
 */
class City
{
    use IdTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Cache(usage="READ_ONLY")
     */
    private Country $country;
    /**
     * @ORM\ManyToOne(targetEntity="Subdivision")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Cache(usage="READ_ONLY")
     */
    private Subdivision $subdivision;
    /**
     * @ORM\Column(type="string", length=127, nullable=false)
     */
    private string $name;
    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    private string $timezone;
    /**
     * @ORM\Column(type="integer", nullable=false, unique=true, options={"unsigned": true})
     */
    private int $externalId;

    public function __construct(Country $country, Subdivision $subdivision, int $id, string $name, string $timezone)
    {
        $this->id = Uuid::uuid4();
        $this->externalId = $id;
        $this->country = $country;
        $this->subdivision = $subdivision;
        $this->name = $name;
        $this->timezone = $timezone;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }
    public function getName(): string
    {
        return $this->name;
    }
}
