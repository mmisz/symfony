<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class SecurityController.
 *
 * @Route("/")
 */
class SecurityController extends AbstractController
{
    /**
     * action homepage.
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @Route("/", name="homepage")
     */
    public function homepage(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('to_do_index');
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * Action login.
     *
     * @param AuthenticationUtils $authenticationUtils
     * @param TranslatorInterface $translator
     * @return Response
     *
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, TranslatorInterface $translator): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('to_do_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $this->addFlash('success', 'message_logged_successfully');

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Action logout.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        return $this->redirectToRoute('app_login');
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
