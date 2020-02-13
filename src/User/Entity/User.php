<?php

declare(strict_types=1);

namespace User\Entity;

use Core\Entity\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;
use User\Security\Roles;

/**
 * @ORM\Entity(repositoryClass="User\Repository\UserRepository")
 * @ORM\Table(name="`user`")
 * @ORM\ChangeTrackingPolicy(value="DEFERRED_EXPLICIT")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class User implements UserInterface
{
    use IdTrait;

    /**
     * @var string
     * @ORM\Column(type="string", length=254, nullable=false, unique=true)
     */
    private string $email;
    /**
     * @var string
     * @ORM\Column(type="string", length=97, nullable=false)
     */
    private string $password;
    /**
     * @var array
     * @ORM\Column(type="json", nullable=false)
     */
    private array $roles = [];

    private function __construct(string $email, array $roles = [Roles::ROLE_USER])
    {
        $this->id = Uuid::uuid4();
        $this->email = $email;
        $this->roles = $roles;
    }

    public static function create(string $email, callable $encoder, array $roles = [Roles::ROLE_USER]): self
    {
        $user = new static($email, $roles);
        $user->password = \call_user_func($encoder, $user);

        return $user;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return '';
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
    }
}
