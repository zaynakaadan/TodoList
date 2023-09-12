<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    
    #[Route('/login', name:'login')]
     
    public function loginAction(Request $request,  AuthenticationUtils $authenticationUtils)
    {
        //$authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    
    #[Route('/login_check', name:'login_check')]
     
    public function loginCheck(): Response
    {
        // This code is never executed.
        return $this->redirectToRoute('task_list');
    }

    #[Route('/logout', name:'logout')]
    
    public function logoutCheck(): Response
    {
        // This code is never executed.
        return new Response('Logout Check');
    }
}
