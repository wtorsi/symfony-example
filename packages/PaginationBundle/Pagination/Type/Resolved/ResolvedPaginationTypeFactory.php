<?php declare(strict_types=1);

namespace Dev\PaginationBundle\Pagination\Type\Resolved;

use Dev\PaginationBundle\Pagination\Type\PaginationTypeInterface;

class ResolvedPaginationTypeFactory implements ResolvedPaginationTypeFactoryInterface
{
    public function createResolvedPaginationType(PaginationTypeInterface $pagination): ResolvedPaginationTypeInterface
    {
        return new ResolvedPaginationType($pagination);
    }
}