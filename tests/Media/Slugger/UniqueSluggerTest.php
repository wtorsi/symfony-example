<?php

namespace Tests\Media\Slugger;

use Media\Entity\Link;
use Media\Slugger\SluggerInterface;
use Media\Slugger\UniqueSlugger;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Tests\KernelTestCase;
use Tests\Media\Entity\DefaultEntity;

class UniqueSluggerTest extends KernelTestCase
{
    public function testSluggify()
    {
        $slugger = self::$container->get(SluggerInterface::class);
        $slug = $slugger->slugify($string = 'test', [
            'class' => Link::class,
            'field' => 'hash',
            'length' => 10,
        ]);

        $this->assertEquals(10, \mb_strlen($slug));
    }

    public function testSluggifyUnique()
    {
        $slugger = self::$container->get(UniqueSlugger::class);

        $slug1 = $slugger->slugify($string = 'test', [
            'class' => Link::class,
            'field' => 'hash',
            'length' => 10,
        ]);

        $slug2 = $slugger->slugify($string = 'test', [
            'class' => Link::class,
            'field' => 'hash',
            'length' => 10,
        ]);

        $this->assertNotEquals($slug1, $slug2);
    }

    public function testLength(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $slugger = self::$container->get(SluggerInterface::class);
        $slugger->slugify($string = 'test', [
            'class' => Link::class,
            'field' => 'field',
        ]);
    }

    public function testMisConfig(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $slugger = self::$container->get(SluggerInterface::class);
        $slugger->slugify($string = 'test');
    }

    public function testMisConfigClass(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $slugger = self::$container->get(SluggerInterface::class);
        $slugger->slugify($string = 'test', [
            'class' => DefaultEntity::class,
            'field' => 'field',
        ]);
    }

    public function testMisConfigField(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $slugger = self::$container->get(SluggerInterface::class);
        $slugger->slugify($string = 'test', [
            'class' => Link::class,
            'field' => 'field',
        ]);
    }
}