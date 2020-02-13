<?php

namespace Media\Controller;

use Media\Form\Dto\LinkDto;
use Media\Form\Type\LinkType;
use Media\Processor\LinkProcessor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/link")
 * @IsGranted("ROLE_USER")
 */
class LinkController extends \AbstractController
{
    /**
     * @Route("/create", name="media_link_create", methods={"POST"}, condition="request.isXmlHttpRequest()")
     */
    public function create(Request $request, LinkProcessor $processor): Response
    {
        $dto = new LinkDto($this->getUser());
        $form = $this->createForm(LinkType::class, $dto);

        return $this->createFormResponse($form, $request, function () use ($processor, $dto): Response {
            $processor->create($dto);

            return $this->createSuccessRedirectToRouteResponse('page_index');
        });
    }
}