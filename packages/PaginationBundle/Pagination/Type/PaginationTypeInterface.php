<?php

declare(strict_types=1);

namespace Dev\PaginationBundle\Pagination\Type;

use Dev\PaginationBundle\Pagination\PaginationInterface;
use Dev\PaginationBundle\Pagination\View\PaginationView;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface PaginationTypeInterface
{
    /**
     * @param PaginationView      $view
     * @param PaginationInterface $pagination
     * @param array               $options
     */
    public function buildView(PaginationView $view, PaginationInterface $pagination, array $options): void;

    /**
     * @param PaginationView      $view
     * @param PaginationInterface $pagination
     * @param array               $options
     */
    public function finishView(PaginationView $view, PaginationInterface $pagination, array $options): void;

    /**
     * @param PaginationInterface $pagination
     * @param array               $options
     */
    public function buildPagination(PaginationInterface $pagination, array $options): void;

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void;
}
