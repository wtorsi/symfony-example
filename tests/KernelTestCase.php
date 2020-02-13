<?php declare(strict_types=1);

namespace Tests;

use Doctrine\ORM\EntityManagerInterface;
use Media\Entity\Link;
use Media\Slugger\SluggerInterface;
use User\Entity\User;

abstract class KernelTestCase extends \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
{
    public function getUser(): User
    {
        return self::$container->get(EntityManagerInterface::class)->getRepository(User::class)->findOneBy([]);
    }

    protected function setUp(): void
    {
        self::bootKernel();
    }

    protected function createLink(): Link
    {
        $user = $this->getUser();
        $slugger = self::$container->get(SluggerInterface::class);
        $hash = $slugger->slugify('url', ['class' => Link::class, 'field' => 'hash']);
        $link = new Link($user, 'url', $hash);
        $em = self::$container->get(EntityManagerInterface::class);
        $em->persist($link);
        $em->flush();

        return $link;
    }
}