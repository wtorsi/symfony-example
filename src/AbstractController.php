<?php

declare(strict_types=1);

use Dev\PaginationBundle\Pagination\PaginationFactory;
use Dev\PaginationBundle\Pagination\PaginationInterface;
use Dev\PaginationBundle\Pagination\Type\RangeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    use ContainerTrait;

    public static function getSubscribedServices()
    {
        return \array_merge(parent::getSubscribedServices(), [
            'translator' => '?'.TranslatorInterface::class,
            PaginationFactory::class,
        ]);
    }


    public function getPagination(array $options = []): PaginationInterface
    {
        return $this->createPagination(RangeType::class, $options);
    }

    protected function cacheResponse(Response $response, string $checksum = null, int $lifetime = 300): void
    {
        $response->setPublic();
        $response->setVary('Accept-Encoding');
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->setSharedMaxAge($lifetime);

        if (null !== $checksum) {
            $response->setEtag($checksum);
        }
    }

    protected function renderCached(Request $request, string $view, array $parameters = [], int $lifetime = 300): Response
    {
        $response = $this->render($view, $parameters);
        $this->cacheResponse($response, \md5($response->getContent()), $lifetime);
        $response->isNotModified($request);

        return $response;
    }
}
