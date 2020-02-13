<?php

declare(strict_types=1);

namespace Dev\PaginationBundle\Pagination;

use Dev\PaginationBundle\Pagination\Type\Resolved\ResolvedPaginationTypeInterface;
use Dev\PaginationBundle\Pagination\View\PaginationView;

class Pagination implements PaginationInterface
{
    /**
     * @var int
     */
    private int $page;
    /**
     * @var int
     */
    private int $amountPerPage;
    /**
     * @var int
     */
    private int $elementsCount = 0;
    /**
     * @var ResolvedPaginationTypeInterface
     */
    private ResolvedPaginationTypeInterface $type;
    /**
     * @var array
     */
    private array $options;

    public function __construct(ResolvedPaginationTypeInterface $type, array $options = [])
    {
        $this->type = $type;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function createView(): PaginationView
    {
        $view = $this->type->createView($this);

        $this->type->buildView($view, $this, $this->options);

        return $view;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): PaginationInterface
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getPerPageLimit(): ?int
    {
        return $this->amountPerPage;
    }

    public function setPerPageLimit(int $amountPerPage): PaginationInterface
    {
        $this->amountPerPage = $amountPerPage;

        return $this;
    }

    public function getElementsCount(): int
    {
        return $this->elementsCount;
    }

    public function setElementsCount(int $totalAmount): PaginationInterface
    {
        $this->elementsCount = $totalAmount;

        return $this;
    }

    public function getPagesCount(): int
    {
        return (int) \ceil($this->elementsCount / $this->amountPerPage);
    }

    public function getOffset(): int
    {
        return \abs($this->page - 1) * $this->amountPerPage;
    }
}
