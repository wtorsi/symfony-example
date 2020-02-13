<?php

namespace Media\Repository;

use Dev\PaginationBundle\PagingInterface;
use Dev\PaginationBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Media\Entity\LinkStatistic;

class LinkStatisticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PagingInterface $paging)
    {
        parent::__construct($registry, $paging, LinkStatistic::class);
    }
}