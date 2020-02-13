<?php declare(strict_types=1);

namespace Tests;

use Doctrine\ORM\EntityManagerInterface;
use User\Entity\User;

abstract class WebTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{
    public function getUser(): User
    {
        return self::$container->get(EntityManagerInterface::class)->getRepository(User::class)->findOneBy([]);
    }

    protected static function createClient(array $options = [], array $server = [])
    {
        return parent::createClient();
//        return parent::createClient([
//            'environment' => 'test',
//            'debug' => true,
//        ],[
//            'HTTP_HOST' => 'ya.wip',
//        ]);
    }
}