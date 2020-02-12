<?php declare(strict_types=1);

namespace Media\Controller;

use Media\Collector\LinkStatisticCollector;
use Media\Entity\Link;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LinkController extends \AbstractController
{
    /**
     * @Route("/{hash}", name="link_index", methods={"GET"})
     */
    public function index(Request $request, Link $link, LinkStatisticCollector $collector): RedirectResponse
    {
        $collector->open($link, $request);

        return $this->redirect($link->getUrl());
    }
}