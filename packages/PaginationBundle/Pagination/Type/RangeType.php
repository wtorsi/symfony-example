<?php

declare(strict_types=1);

namespace Dev\PaginationBundle\Pagination\Type;

use Dev\PaginationBundle\Exception\InvalidOptionsException;
use Dev\PaginationBundle\Pagination\PaginationInterface;
use Dev\PaginationBundle\Pagination\View\PaginationView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RangeType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'page_range' => 8,
        ]);

        $resolver->setAllowedTypes('page_range', 'int');
        $resolver->setNormalizer('page_range', function (Options $options, $value) {
            $limit = \abs((int) $value);
            if (!$limit) {
                throw new InvalidOptionsException("Parameter 'page_range' must be a positive int.");
            }

            return $limit;
        });

        parent::configureOptions($resolver);
    }

    public function buildView(PaginationView $view, PaginationInterface $pagination, array $options = []): void
    {
        $pagesCount = $pagination->getPagesCount();
        $current = $pagination->getPage();

        if ($pagesCount < $current) {
            $current = $pagesCount;
        }

        $pageRange = $options['page_range'];
        if ($pageRange > $pagesCount) {
            $pageRange = $pagesCount;
        }

        $delta = \ceil($pageRange / 2);

        if ($current - $delta > $pagesCount - $pageRange) {
            $pages = \range($pagesCount - $pageRange + 1, $pagesCount);
        } else {
            if ($current - $delta < 0) {
                $delta = $current;
            }

            $offset = $current - $delta;
            $pages = \range($offset + 1, $offset + $pageRange);
        }

        $proximity = \floor($pageRange / 2);
        $startPage = $current - $proximity;
        $endPage = $current + $proximity;

        if ($startPage < 1) {
            $endPage = \min($endPage + (1 - $startPage), $pagesCount);
            $startPage = 1;
        }

        if ($endPage > $pagesCount) {
            $startPage = \max($startPage - ($endPage - $pagesCount), 1);
            $endPage = $pagesCount;
        }

        $view->vars = [
            'current' => $current,
            'pagesCount' => $pagesCount,
            'elementsCount' => $pagination->getElementsCount(),

            'startPage' => $startPage,
            'endPage' => $endPage,
            //navigation
            'previous' => $current - 1 > 0 ? $current - 1 : null,
            'next' => $current + 1 <= $pagesCount ? $current + 1 : null,
            'pages' => $pages,
        ];
    }
}
