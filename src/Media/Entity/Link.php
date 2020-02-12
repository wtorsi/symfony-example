<?php declare(strict_types=1);

namespace Media\Entity;

use Core\Entity\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use User\Entity\User;

/**
 * @ORM\Entity()
 * @ORM\ChangeTrackingPolicy(value="DEFERRED_EXPLICIT")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Link
{
    use IdTrait;

    /**
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     */
    private User $user;
    /**
     * @ORM\Column(type="string", length=4096, nullable=false)
     */
    private string $url;
    /**
     * @ORM\Column(type="string", length=10, nullable=false, unique=true)
     */
    private string $hash;
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $expirationDatetime = null;

    public function __construct(User $user, string $url, string $hash, ?\DateTimeImmutable $expirationDatetime)
    {
        $this->id = Uuid::uuid4();
        $this->user = $user;
        $this->url = $url;
        $this->hash = $hash;
        $this->expirationDatetime = $expirationDatetime;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}