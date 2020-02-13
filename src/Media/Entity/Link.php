<?php

declare(strict_types=1);

namespace Media\Entity;

use Core\Entity\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Media\Form\Dto\LinkDto;
use Media\Slugger\SluggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(readOnly=true, repositoryClass="Media\Repository\LinkRepository")
 * @ORM\ChangeTrackingPolicy(value="DEFERRED_EXPLICIT")
 * @ORM\Cache(usage="READ_ONLY")
 */
class Link
{
    use IdTrait;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    private \DateTimeImmutable $createdDatetime;
    /**
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     */
    private UserInterface $user;
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

    public function __construct(UserInterface $user, string $url, string $hash, ?\DateTimeImmutable $expirationDatetime = null)
    {
        $this->id = Uuid::uuid4();
        $this->user = $user;
        $this->url = $url;
        $this->hash = $hash;
        $this->createdDatetime = new \DateTimeImmutable();
        $this->expirationDatetime = $expirationDatetime;
    }

    public static function factory(LinkDto $dto, SluggerInterface $slugger): self
    {
        return new static(
            $dto->getUser(),
            $dto->url,
            $slugger->slugify($dto->url, ['length' => 10, 'class' => static::class, 'field' => 'hash']),
            $dto->expirationDatetime
        );
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedDatetime(): \DateTimeImmutable
    {
        return $this->createdDatetime;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getExpirationDatetime(): ?\DateTimeImmutable
    {
        return $this->expirationDatetime;
    }

    public function isExpired(): bool
    {
        return null !== $this->expirationDatetime && $this->expirationDatetime < new \DateTimeImmutable();
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getHash(): string
    {
        return $this->hash;
    }
}
