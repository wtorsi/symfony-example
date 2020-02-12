<?php

namespace Location\Entity;

use Core\Entity\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"name", "subdivision_id"})})
 * @ORM\ChangeTrackingPolicy(value="DEFERRED_EXPLICIT")
 * @ORM\Cache(usage="READ_ONLY")
 */
class City
{
    use IdTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Subdivision")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Cache(usage="READ_ONLY")
     */
    private Country $country;
    /**
     * @ORM\ManyToOne(targetEntity="Subdivision")
     * @ORM\JoinColumn(nullable=true)
     * @ORM\Cache(usage="READ_ONLY")
     */
    private ?Subdivision $subdivision = null;
    /**
     * @ORM\Column(type="string", length=127, nullable=false)
     */
    private string $name;
    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    private string $timezone;

    public function __construct(Country $country, ?Subdivision $subdivision, string $name, string $timezone)
    {
        $this->id = Uuid::uuid4();
        $this->country = $country;
        $this->subdivision = $subdivision;
        $this->name = $name;
        $this->timezone = $timezone;
    }
}
