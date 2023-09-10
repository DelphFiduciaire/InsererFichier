<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/', name: 'app_main')]
    public function index(UserInterface $user): Response
    {
        $role_user = $user->getRoles();


        if ($role_user[0] === "ROLE_ADMIN")
        {
            return $this->redirectToRoute('app_info_client_new');

        }
        elseif($role_user[0] === "ROLE_CLIENT")
        {
            return $this->redirectToRoute('app_fichier_index');

        }
        else
        {
            return $this->redirectToRoute('app_info_client_index');
        }
    }

    #[Route(path: '/loginn', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
