<?php
/**
 * Security controller.
 */

namespace App\Controller;

use App\Form\HomeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController.
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'searchForm' => $searchForm->createView(),]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
