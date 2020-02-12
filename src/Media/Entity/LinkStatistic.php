<?php declare(strict_types=1);

namespace Media\Entity;

use Core\Entity\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Location\Entity\City;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\ChangeTrackingPolicy(value="DEFERRED_EXPLICIT")
 * @ORM\Cache(usage="READ_ONLY")
 */
class LinkStatistic
{
    use IdTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Link")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     */
    private Link $link;
    /**
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    private \DateTimeImmutable $datetime;
    /**
     * @var City
     * @ORM\ManyToOne(targetEntity="Location\Entity\City")
     * @ORM\JoinColumn(nullable=true)
     * @ORM\Cache(usage="READ_ONLY")
     */
    private ?City $city = null;
    /**
     * @ORM\Column(type="json", nullable=false)
     */
    private array $meta;

    public function __construct(Link $link, ?City $city, array $meta = [])
    {
        $this->id = Uuid::uuid4();
        $this->datetime = new \DateTimeImmutable();
        $this->link = $link;
        $this->city = $city;
        $this->meta = $meta;
    }
}