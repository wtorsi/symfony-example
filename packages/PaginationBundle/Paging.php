<?php

declare(strict_types=1);

namespace Dev\PaginationBundle;

use Dev\PaginationBundle\Exception\LogicException;
use Dev\PaginationBundle\Exception\RuntimeException;
use Dev\PaginationBundle\Pagination\PaginationInterface;
use Dev\PaginationBundle\Paginator\PaginatorRegistry;

class Paging implements PagingInterface
{
    /**
     * @var PaginatorRegistry
     */
    private PaginatorRegistry $registry;

    public function __construct(PaginatorRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function paginate($target, PaginationInterface $pagination, array $options = []): iterable
    {
        if (null === $pagination->getPerPageLimit()) {
            throw new LogicException('Invalid per page limit, it must be a positive number');
        }

        $paginator = $this->registry->getSupportedPaginator($target);
        if (null === $paginator) {
            throw new RuntimeException('No supported pager for target "%s".', \is_object($target) ? \get_class($target) : \gettype($target));
        }

        return $paginator->paginate($target, $pagination, $options);
    }
}
