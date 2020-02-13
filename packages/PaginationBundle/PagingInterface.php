<?php

declare(strict_types=1);

namespace Dev\PaginationBundle;

use Dev\PaginationBundle\Pagination\PaginationInterface;

interface PagingInterface
{
    /**
     * @param mixed $target - anything what needs to be paginated
     *
     * @return iterable current page of elements
     */
    public function paginate($target, PaginationInterface $pagination, array $options = []): iterable;
}
