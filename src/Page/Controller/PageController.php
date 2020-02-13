<?php

namespace Page\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Media\Entity\Link;
use Media\Form\Type\LinkType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class PageController extends \AbstractController
{
    /**
     * @Route("/", name="page_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $em): Response
    {
        $form = $this->createForm(LinkType::class, null, [
            'action' => $this->generateUrl('media_link_create'),
        ]);
        $entities = $em->getRepository(Link::class)->listBy(
            ['user' => $this->getUser()],
            ['createdDatetime' => 'DESC'],
            $pagination = $this->getPagination(['limit' => 5])
        );

        return $this->render('app/app/index.html.twig', [
            'entities' => $entities,
            'form' => $form->createView(),
            'pagination' => $pagination->createView()
        ]);
    }
}