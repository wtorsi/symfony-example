<?php

declare(strict_types=1);

namespace Dev\PaginationBundle\Paginator;

use Dev\PaginationBundle\Pagination\PaginationInterface;

interface PaginatorInterface
{
    public function supports($target): bool;

    public function paginate($target, PaginationInterface $pagination, array $options = []): iterable;
}
