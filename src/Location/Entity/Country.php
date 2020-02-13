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
class Country
{
    use IdTrait;

    /**
     * @ORM\Column(type="string", length=127, nullable=false, unique=true)
     */
    private string $name;
    /**
     * @ORM\Column(type="string", length=127, nullable=false, unique=true)
     */
    private string $isoCode;

    public function __construct(string $isoCode, string $name)
    {
        $this->id = Uuid::uuid4();
        $this->isoCode = $isoCode;
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
