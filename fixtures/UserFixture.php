<?php

namespace Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use User\Entity\User;

class UserFixture extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = User::create('dev@email.ru', fn(User $user) => $this->encoder->encodePassword($user, 'dev'));
        $manager->persist($user);

        $user = User::create('prod@email.ru', fn(User $user) => $this->encoder->encodePassword($user, 'prod'));
        $manager->persist($user);

        $user = User::create('test@email.ru', fn(User $user) => $this->encoder->encodePassword($user, 'test'));
        $manager->persist($user);

        $manager->flush();
    }
}