<?php declare(strict_types=1);

namespace Dev\PaginationBundle\Pagination\Type\Resolved;

use Dev\PaginationBundle\Pagination\Type\PaginationTypeInterface;

interface ResolvedPaginationTypeFactoryInterface
{
    public function createResolvedPaginationType(PaginationTypeInterface $pagination): ResolvedPaginationTypeInterface;
}