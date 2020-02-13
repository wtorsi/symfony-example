<?php

declare(strict_types=1);

namespace Dev\PaginationBundle\Repository;

use Dev\PaginationBundle\Exception\LogicException;
use Dev\PaginationBundle\PagingInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class ServiceEntityRepository extends EntityRepository implements ServiceEntityRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, ?PagingInterface $paging, string $entityClass)
    {
        /** @var EntityManagerInterface $manager */
        $manager = $registry->getManagerForClass($entityClass);

        if (null === $manager) {
            throw new LogicException(\sprintf('Could not find the entity manager for class "%s". Check your Doctrine configuration to make sure it is configured to load this entity’s metadata.', $entityClass));
        }

        parent::__construct($manager, $manager->getClassMetadata($entityClass), $paging);
    }
}
