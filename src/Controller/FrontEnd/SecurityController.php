<?php

namespace App\Controller\FrontEnd;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     *
     * @param AuthenticationUtils   $authenticationUtils
     * @param UrlGeneratorInterface $urlGenerator
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, UrlGeneratorInterface $urlGenerator): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            return new RedirectResponse($urlGenerator->generate('default'));
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('backend/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     *
     * @throws \Exception
     */
    public function logout()
    {
        throw new \Exception('Will be intercepted before getting here');
    }
}
