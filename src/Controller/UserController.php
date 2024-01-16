<?php

namespace App\Controller;

use App\Entity\FichierBilan;
use App\Entity\FichierDemande;
use App\Entity\InfoClient;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $user = new User();
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'user'=>$user->getUserIdentifier()
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(UserPasswordHasherInterface $userPasswordHasher, Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $idUser = $entityManager->getRepository(User::class)->findOneBy(['email'=> 'orel@admin.com']);

            $clients = $entityManager->getRepository(InfoClient::class)->findBy(['id_user'=>$user->getId()]);
            $fichiersBilans = $entityManager->getRepository(FichierBilan::class)->findBy(['id_user'=>$user->getId()]);
            $fichiersDemandes = $entityManager->getRepository(FichierDemande::class)->findBy(['id_user'=>$user->getId()]);

            foreach ($clients as $client)
            {
                $client->setIdUser($idUser);
                $entityManager->persist($client);
                $entityManager->flush();
            }
            foreach ($fichiersBilans as $fichierBilan)
            {
                $fichierBilan->setIdUser($idUser);
                $entityManager->persist($fichierBilan);
                $entityManager->flush();
            }
            foreach ($fichiersDemandes as $fichierDemande )
            {
                $fichierDemande->setIdUser($idUser);
                $entityManager->persist($fichierDemande);
                $entityManager->flush();
            }
            $entityManager->remove($user);
            $entityManager->flush();

            }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
