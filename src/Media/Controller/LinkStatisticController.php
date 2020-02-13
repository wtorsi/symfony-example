<?php

namespace Media\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Media\Entity\Link;
use Media\Entity\LinkStatistic;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/link/stats")
 */
class LinkStatisticController extends \AbstractController
{
    /**
     * @Route("/{id}", name="media_link_statistic_view", methods={"GET"})
     */
    public function view(EntityManagerInterface $em, Link $link): Response
    {
        $entities = $em->getRepository(LinkStatistic::class)->listBy(
            ['link' => $link],
            ['datetime' => 'DESC'],
            $pagination = $this->getPagination(['limit' => 8])
        );

        return $this->render('media/link_statistics/view.html.twig', [
            'entity' => $link,
            'entities' => $entities,
            'pagination' => $pagination->createView(),
        ]);
    }
}