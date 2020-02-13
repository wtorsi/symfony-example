<?php declare(strict_types=1);

namespace Dev\PaginationBundle\Pagination;

class PaginationFactory
{
    /**
     * @var PaginationRegistry
     */
    private $registry;

    public function __construct(PaginationRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function create(string $name, array $options = []): PaginationInterface
    {
        $type = $this->registry->getType($name);
        $pagination = $type->buildPagination($options);

        return $pagination;
    }
}