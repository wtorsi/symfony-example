<?php

declare(strict_types=1);

namespace Dev\PaginationBundle\Pagination;

use Dev\PaginationBundle\Pagination\View\PaginationView;

interface PaginationInterface
{
    /**
     * @param int $pageNumber
     *
     * @return PaginationInterface
     */
    public function setPage(int $pageNumber): PaginationInterface;

    /**
     * @return int
     */
    public function getPage(): ?int;

    /**
     * @return int
     */
    public function getPagesCount(): int;

    /**
     * @param int $amountPerPage
     *
     * @return PaginationInterface
     */
    public function setPerPageLimit(int $amountPerPage): PaginationInterface;

    /**
     * @return int
     */
    public function getPerPageLimit(): ?int;

    /**
     * @param int $numTotal
     *
     * @return PaginationInterface
     */
    public function setElementsCount(int $numTotal): PaginationInterface;

    /**
     * @return int
     */
    public function getElementsCount(): int;

    /**
     * @return int
     */
    public function getOffset(): int;

    /**
     * @return PaginationView
     */
    public function createView(): PaginationView;
}
