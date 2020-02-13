<?php

namespace Tests\App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Media\Entity\Link;
use Media\Slugger\SluggerInterface;
use Tests\WebTestCase;

class AppControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = self::createClient();

        $link = $this->createLink();
        $client->request('GET', '/'.$link->getHash());
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $link = $this->createExpiredLink();
        $client->request('GET', '/'.$link->getHash());
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    private function createLink(): Link
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

    private function createExpiredLink(): Link
    {
        $user = $this->getUser();
        $slugger = self::$container->get(SluggerInterface::class);
        $hash = $slugger->slugify('url', ['class' => Link::class, 'field' => 'hash']);
        $link = new Link($user, 'url', $hash, new \DateTimeImmutable('-1 day'));
        $em = self::$container->get(EntityManagerInterface::class);
        $em->persist($link);
        $em->flush();

        return $link;
    }
}