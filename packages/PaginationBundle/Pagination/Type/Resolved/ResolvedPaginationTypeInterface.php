<?php

declare(strict_types=1);

namespace Dev\PaginationBundle\Pagination\Type\Resolved;

use Dev\PaginationBundle\Pagination\PaginationInterface;
use Dev\PaginationBundle\Pagination\View\PaginationView;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ResolvedPaginationTypeInterface
{
    /**
     * @return OptionsResolver
     */
    public function getOptionsResolver(): OptionsResolver;

    /**
     * @param array $options
     *
     * @return mixed
     */
    public function buildPagination(array $options): PaginationInterface;

    /**
     * @param PaginationInterface $pagination
     *
     * @return PaginationView
     */
    public function createView(PaginationInterface $pagination): PaginationView;

    /**
     * @param PaginationView      $view
     * @param PaginationInterface $pagination
     * @param array               $options
     */
    public function buildView(PaginationView $view, PaginationInterface $pagination, array $options): void;
}
