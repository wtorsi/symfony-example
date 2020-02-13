<?php declare(strict_types=1);

namespace Dev\PaginationBundle;

use Dev\PaginationBundle\Pagination\PaginationFactory;
use Dev\PaginationBundle\Pagination\PaginationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @property ContainerInterface $container
 */
trait PaginationTrait
{
    protected function createPagination(string $type, array $options = []): PaginationInterface
    {
        return $this->container->get(PaginationFactory::class)->create($type, $options);
    }
}