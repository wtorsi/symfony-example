<?php declare(strict_types=1);

namespace Dev\PaginationBundle\Pagination;

use Dev\PaginationBundle\Exception\InvalidArgumentException;
use Dev\PaginationBundle\Pagination\Type\PaginationTypeInterface;
use Dev\PaginationBundle\Pagination\Type\Resolved\ResolvedPaginationTypeFactoryInterface;
use Dev\PaginationBundle\Pagination\Type\Resolved\ResolvedPaginationTypeInterface;

class PaginationRegistry
{
    /**
     * @var PaginationTypeInterface[]
     */
    private $types = [];
    /**
     * @var ResolvedPaginationTypeInterface[]
     */
    private $resolved = [];
    /**
     * @var ResolvedPaginationTypeFactoryInterface
     */
    private $factory;

    public function __construct(ResolvedPaginationTypeFactoryInterface $factory, iterable $types)
    {
        foreach ($types as $type) {
            $this->types[\get_class($type)] = $type;
        }
        $this->factory = $factory;
    }

    public function getType(string $name): ResolvedPaginationTypeInterface
    {
        // todo remove this, add possibility to create multiple paginations per request
        if (!isset($this->resolved[$name])) {
            $type = null;

            if (!isset($this->types[$name])) {
                throw new InvalidArgumentException(sprintf('The pagination "%s" can not be loaded.', $name));
            }

            $this->resolved[$name] = $this->resolve($this->types[$name]);
        }

        return $this->resolved[$name];
    }

    /**
     * @param PaginationTypeInterface $type
     * @return ResolvedPaginationTypeInterface The resolved pagination
     */
    private function resolve(PaginationTypeInterface $type): ResolvedPaginationTypeInterface
    {
        return $this->factory->createResolvedPaginationType($type);
    }
}