<?php

declare(strict_types=1);

namespace Dev\PaginationBundle\Paginator;

use Dev\PaginationBundle\Pagination\PaginationInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class QueryPaginator extends AbstractPaginator
{
    /**
     * @var string
     */
    const HINT_FETCH_JOIN_COLLECTION = 'pagination.fetch_join_collection';

    /**
     * @throws \Exception
     */
    public function paginate($target, PaginationInterface $pagination, array $options = []): iterable
    {
        if ($target instanceof QueryBuilder) {
            $target = $target->getQuery();
        }

        $target
            ->setFirstResult($pagination->getOffset())
            ->setMaxResults($pagination->getPerPageLimit());

        $fetchJoinCollection = false;
        if ($target->hasHint(self::HINT_FETCH_JOIN_COLLECTION)) {
            $fetchJoinCollection = (bool) $target->getHint(self::HINT_FETCH_JOIN_COLLECTION);
        }

        $paginator = new Paginator($target, $fetchJoinCollection);
        $pagination->setElementsCount(\count($paginator));

        return $paginator->getIterator();
    }

    public function supports($target): bool
    {
        return \class_exists('Doctrine\ORM\Tools\Pagination\Paginator')
               && ($target instanceof Query || $target instanceof QueryBuilder);
    }
}
