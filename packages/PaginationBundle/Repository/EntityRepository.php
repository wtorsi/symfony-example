<?php

declare(strict_types=1);

namespace Dev\PaginationBundle\Repository;

use Dev\PaginationBundle\Exception\InvalidArgumentException;
use Dev\PaginationBundle\Exception\LogicException;
use Dev\PaginationBundle\Pagination\PaginationInterface;
use Dev\PaginationBundle\PagingInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntityRepository extends \Doctrine\ORM\EntityRepository implements ServiceEntityRepositoryInterface
{
    /**
     * @var PagingInterface|null
     */
    private ?PagingInterface $paging = null;

    public function __construct(EntityManagerInterface $em, Mapping\ClassMetadata $class, ?PagingInterface $paging = null)
    {
        $this->paging = $paging;
        parent::__construct($em, $class);
    }

    public function getOneBy($criteria = null, array $orderBy = null): object
    {
        $this->assertCriteria($criteria);

        if ($criteria instanceof Criteria) {
            $criteria->orderBy($orderBy ?: [])->setMaxResults(1);
            $entity = $this->matching($criteria)->first();
        } else {
            $entity = $this->findOneBy($criteria, $orderBy);
        }

        if (null === $entity) {
            throw new NotFoundHttpException();
        }

        return $entity;
    }

    /**
     * @param array|null                   $orderBy
     * @param PaginationInterface|int|null $pagination
     * @return iterable
     */
    public function list(array $orderBy = null, $pagination = null): iterable
    {
        return $this->listBy([], $orderBy, $pagination);
    }

    /**
     * @param null                         $criteria
     * @param PaginationInterface|int|null $pagination
     *
     * @return array|Collection
     */
    public function listBy($criteria = null, ?array $orderBy = null, $pagination = null): iterable
    {
        $this->assertCriteria($criteria);
        $this->assertPagination($pagination);

        if (null !== $pagination && $pagination instanceof PaginationInterface) {
            if (null === $this->paging) {
                throw new LogicException(sprintf('To use pagination with EntityRepository you should create custom EntityRepository of entity and inject %s as its argument.', PagingInterface::class));
            }

            return $this->paging->paginate($this, $pagination, [
                'criteria' => $criteria,
                'orderBy' => $orderBy,
            ]);
        }

        if ($criteria instanceof Criteria) {
            $criteria->orderBy($orderBy ?: [])->setMaxResults($pagination);

            return $this->matching($criteria);
        }

        return $this->findBy($criteria, $orderBy, $pagination);
    }

    private function assertCriteria($criteria = null): void
    {
        if (null !== $criteria && !\is_array($criteria) && !$criteria instanceof Criteria) {
            throw new InvalidArgumentException(sprintf('Criteria must be null, array or %s', Criteria::class));
        }
    }

    private function assertPagination($pagination = null): void
    {
        if (null !== $pagination && !\is_int($pagination) && !$pagination instanceof PaginationInterface) {
            throw new InvalidArgumentException(sprintf('Pagination must be null, int or %s', PaginationInterface::class));
        }
    }
}
