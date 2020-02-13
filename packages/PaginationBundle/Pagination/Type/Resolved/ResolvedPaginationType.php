<?php declare(strict_types=1);

namespace Dev\PaginationBundle\Pagination\Type\Resolved;

use Dev\PaginationBundle\Pagination\Pagination;
use Dev\PaginationBundle\Pagination\PaginationInterface;
use Dev\PaginationBundle\Pagination\Type\PaginationTypeInterface;
use Dev\PaginationBundle\Pagination\View\PaginationView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResolvedPaginationType implements ResolvedPaginationTypeInterface
{
    /**
     * @var OptionsResolver
     */
    private $optionsResolver;
    /**
     * @var PaginationInterface
     */
    private $type;

    public function __construct(PaginationTypeInterface $type)
    {
        $this->type = $type;
    }

    /**
     * @return \Symfony\Component\OptionsResolver\OptionsResolver The options resolver
     */
    public function getOptionsResolver(): OptionsResolver
    {
        if (null === $this->optionsResolver) {
            $this->optionsResolver = new OptionsResolver();

            $this->type->configureOptions($this->optionsResolver);
        }

        return $this->optionsResolver;
    }

    public function buildPagination(array $options): PaginationInterface
    {
        $options = $this->getOptionsResolver()->resolve($options);
        $pagination = new Pagination($this, $options);
        $this->type->buildPagination($pagination, $options);

        return $pagination;
    }

    /**
     * @param PaginationInterface $pagination
     * @return PaginationView
     */
    public function createView(PaginationInterface $pagination): PaginationView
    {
        return new PaginationView();
    }

    public function buildView(PaginationView $view, PaginationInterface $pagination, array $options): void
    {
        $this->type->buildView($view, $pagination, $options);
        $this->type->finishView($view, $pagination, $options);
    }
}