<?php

declare(strict_types=1);

namespace User\Provider;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use User\Entity\User;

class UserProvider implements UserProviderInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function loadUserByUsername($username): User
    {
        $er = $this->em->getRepository(User::class);
        $user = $er->findOneBy(['email' => \mb_strtolower($username)]);

        if (null === $user) {
            throw new UsernameNotFoundException();
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        /** @var User $user */
        $er = $this->em->getRepository(User::class);

        $refreshedUser = $er->find($id = $user->getId());

        if (null === $refreshedUser) {
            throw new UsernameNotFoundException(sprintf('User with id %s not found', \json_encode($id)));
        }

        return $refreshedUser;
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
