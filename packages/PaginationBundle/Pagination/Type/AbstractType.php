<?php

declare(strict_types=1);

namespace Dev\PaginationBundle\Pagination\Type;

use Dev\PaginationBundle\Exception\InvalidOptionsException;
use Dev\PaginationBundle\Exception\RuntimeException;
use Dev\PaginationBundle\Pagination\PaginationInterface;
use Dev\PaginationBundle\Pagination\View\PaginationView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractType implements PaginationTypeInterface
{
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param PaginationView      $view
     * @param PaginationInterface $pagination
     * @param array               $options
     */
    public function buildView(PaginationView $view, PaginationInterface $pagination, array $options): void
    {
    }

    public function finishView(PaginationView $view, PaginationInterface $pagination, array $options): void
    {
        $view->vars = array_replace_recursive($view->vars, [
            'page_parameter' => $options['page_parameter'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'page_parameter' => 'p',
            'limit' => 12,
        ]);

        $resolver->setAllowedTypes('page_parameter', 'string');
        $resolver->setAllowedTypes('limit', 'int');
        $resolver->setNormalizer('limit', function (Options $options, $value) {
            $limit = abs((int) $value);
            if (!$limit) {
                throw new InvalidOptionsException("Parameter 'limit' must be a positive int.");
            }

            return $limit;
        });
    }

    public function buildPagination(PaginationInterface $pagination, array $options): void
    {
        $request = $this->requestStack->getMasterRequest();
        if (null === $request) {
            throw new RuntimeException('No master request.');
        }

        $page = abs($request->query->getInt($options['page_parameter'], 1));

        $pagination
            ->setPage($page)
            ->setPerPageLimit($options['limit']);
    }
}
