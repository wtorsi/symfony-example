<?php

declare(strict_types=1);

namespace Dev\PaginationBundle\Paginator;

use ArrayObject;
use Dev\PaginationBundle\Pagination\PaginationInterface;

class ArrayPaginator extends AbstractPaginator
{
    /**
     * @param iterable            $target
     * @param PaginationInterface $pagination
     * @param array               $options
     * @return array|ArrayObject
     */
    public function paginate($target, PaginationInterface $pagination, array $options = []): iterable
    {
        if ($target instanceof ArrayObject) {
            $count = $target->count();
            $items = new ArrayObject(array_slice(
                $target->getArrayCopy(),
                $pagination->getOffset(),
                $pagination->getPerPageLimit()
            ));

            $pagination->setElementsCount($count);

            return $items;
        }

        /** @var $target array */
        $count = count($target);
        $items = array_slice(
            $target,
            $pagination->getOffset(),
            $pagination->getPerPageLimit()
        );

        $pagination->setElementsCount($count);

        return $items;
    }

    public function supports($target): bool
    {
        return is_array($target) || $target instanceof ArrayObject;
    }
}
