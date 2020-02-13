<?php

namespace App\Controller;

use Media\Collector\LinkStatisticCollector;
use Media\Entity\Link;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends \AbstractController
{
    /**
     * @Route("/", name="app_touch", methods={"GET"})
     */
    public function touch(): Response
    {
        return $this->redirectToRoute('page_index');
    }

    /**
     * @Route("/{hash}", name="app_index", methods={"GET"}, requirements={"hash" = "[\d\w]{10}"})
     * @Entity("link", expr="repository.findOneByHash(hash)")
     */
    public function index(Request $request, Link $link, LinkStatisticCollector $collector): RedirectResponse
    {
        if ($link->isExpired()) {
            throw $this->createNotFoundException();
        }

        $collector->open($link, $request);

        return $this->redirect($link->getUrl());
    }
}