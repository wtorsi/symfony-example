<?php declare(strict_types=1);

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

trait IdTrait
{
    /**
     * @var UuidInterface
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true, nullable=false)
     */
    protected UuidInterface $id;

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}