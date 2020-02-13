<?php

namespace Media\Repository;

use Dev\PaginationBundle\PagingInterface;
use Dev\PaginationBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Media\Entity\Link;

class LinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PagingInterface $paging)
    {
        parent::__construct($registry, $paging, Link::class);
    }

    public function findOneByHash(string $hash): ?Link
    {
        return $this->findOneBy(['hash' => $hash]);
    }
}