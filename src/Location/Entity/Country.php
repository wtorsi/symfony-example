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

    public function __construct(string $name)
    {
        $this->id = Uuid::uuid4();
        $this->name = $name;
    }
}
