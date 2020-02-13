<?php

declare(strict_types=1);

namespace Dev\PaginationBundle\Paginator;

use Dev\PaginationBundle\Pagination\PaginationInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class EntityRepositoryPaginator extends AbstractPaginator
{
    /**
     * @param EntityRepository $target
     * @return iterable
     */
    public function paginate($target, PaginationInterface $pagination, array $options = []): iterable
    {
        $criteria = [];
        if (isset($options['criteria'])) {
            $criteria = $options['criteria'];
        }

        $orderBy = null;
        if (isset($options['orderBy'])) {
            $orderBy = $options['orderBy'];
        }

        if ($criteria instanceof Criteria) {
            $collection = $target->matching($criteria);
            $count = $collection->count();
            $pagination->setElementsCount($count);

            $criteria
                ->setFirstResult($pagination->getOffset())
                ->setMaxResults($pagination->getPerPageLimit())
                ->orderBy($orderBy ?: []);

            return $collection;
        }

        $count = $target->count($criteria);
        $pagination->setElementsCount($count);

        return $target->findBy($criteria, $orderBy, $pagination->getPerPageLimit(), $pagination->getOffset());
    }

    public function supports($target): bool
    {
        return $target instanceof EntityRepository;
    }
}
