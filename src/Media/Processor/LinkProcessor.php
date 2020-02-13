<?php

namespace Media\Processor;

use Doctrine\ORM\EntityManagerInterface;
use Media\Entity\Link;
use Media\Form\Dto\LinkDto;
use Media\Slugger\SluggerInterface;
use Media\Slugger\UniqueSlugger;

class LinkProcessor
{
    private EntityManagerInterface $em;
    private SluggerInterface $slugger;

    public function __construct(EntityManagerInterface $em, UniqueSlugger $slugger)
    {
        $this->em = $em;
        $this->slugger = $slugger;
    }

    public function create(LinkDto $dto): void
    {
        $entity = Link::factory($dto, $this->slugger);
        $this->em->persist($entity);
        $this->em->flush();
    }
}
