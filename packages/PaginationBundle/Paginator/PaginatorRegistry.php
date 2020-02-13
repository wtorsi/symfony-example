<?php declare(strict_types=1);

namespace Dev\PaginationBundle\Paginator;

class PaginatorRegistry
{
    /**
     * @var PaginatorInterface[]
     */
    private $paginators;

    public function __construct(iterable $paginators)
    {
        $this->paginators = $paginators;
    }

    public function getSupportedPaginator($target): ?PaginatorInterface
    {
        foreach ($this->paginators as $pager) {
            if ($pager->supports($target)) {
                return $pager;
            }
        }

        return null;
    }
}