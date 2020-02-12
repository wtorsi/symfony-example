<?php

declare(strict_types=1);

namespace Security\Controller;

use Security\Form\Type\LoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/security")
 */
class SecurityController extends \AbstractController
{
    /**
     * @Route("/login", name="security_login", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function login(Request $request): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('page_index');
        }

        $form = $this->createForm(LoginType::class, null, [
            'action' => $this->generateUrl('security_check'),
        ]);

        return $this->render('security/security/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/check", name="security_check", methods={"POST"})
     * @Security("!is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function check(): void
    {
        throw $this->createNotFoundException();
    }

    /**
     * @Route("/logout", name="security_logout", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function logout(): void
    {
    }
}
