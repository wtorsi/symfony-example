<?php declare(strict_types=1);

namespace Media\Messenger\Message;

use Location\Entity\City;
use Media\Entity\Link;

class OpenLinkMessage
{
    private string $id;
    private \DateTimeImmutable $dateTime;
    private ?string $cityId;
    private array $meta;

    public function __construct(string $id, ?string $cityId, array $meta = [])
    {
        $this->id = $id;
        $this->dateTime = new \DateTimeImmutable();
        $this->cityId = $cityId;
        $this->meta = $meta;
    }

    public static function factory(Link $link, ?City $city, array $meta = []): self
    {
        return new static(
            (string) $link->getId(),
            $city ? (string) $city->getId() : null,
            $meta,
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDateTime(): \DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function getCityId(): ?string
    {
        return $this->cityId;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }
}