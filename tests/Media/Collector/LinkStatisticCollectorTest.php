<?php

namespace Tests\Media\Collector;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Media\Collector\LinkStatisticCollector;
use Media\Entity\Link;
use Media\Entity\LinkStatistic;
use Media\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Tests\KernelTestCase;

class LinkStatisticCollectorTest extends KernelTestCase
{
    public function testOpen()
    {
        $collector = self::$container->get(LinkStatisticCollector::class);
        $link = $this->createLink();

        /** @var EntityManager $em */
        $em = self::$container->get(EntityManagerInterface::class);
        $er = $em->getRepository(LinkStatistic::class);

        $this->assertEquals(0, $er->count(['link' => $link]));
        $collector->open($link, new Request());
        $this->assertEquals(1, $er->count(['link' => $link]));
    }


}