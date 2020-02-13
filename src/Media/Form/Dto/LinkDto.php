<?php declare(strict_types=1);

namespace Media\Form\Dto;

use Symfony\Component\Security\Core\User\UserInterface;

class LinkDto
{
    public ?string $url = null;
    public ?\DateTimeImmutable $expirationDatetime = null;
    private UserInterface $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }
}