<?php

namespace App\Controller;

use App\Entity\InfoClient;
use App\Form\InfoClientType;
use App\Repository\InfoClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/info/client')]
class InfoClientController extends AbstractController
{
    #[Route('/', name: 'app_info_client_index', methods: ['GET'])]
    public function index(InfoClientRepository $infoClientRepository): Response
    {
        $user = $this->getUser();
        return $this->render('info_client/index.html.twig', [
            'info_clients' => $infoClientRepository->findAll(),
            'user'=>$user->getUserIdentifier()
        ]);
    }

    #[Route('/new', name: 'app_info_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, InfoClientRepository $infoClientRepository): Response
    {
        $user = $this->getUser();
        $infoClient = new InfoClient();
        $form = $this->createForm(InfoClientType::class, $infoClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $infoClientRepository->save($infoClient, true);

//            $entityManager->persist($infoClient);
//            $entityManager->flush();

            return $this->redirectToRoute('app_info_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('info_client/new.html.twig', [
            'info_client' => $infoClient,
            'form' => $form,
            'user' => $user->getUserIdentifier(),

        ]);
    }

    #[Route('/show/{id}', name: 'app_info_client_show', methods: ['GET'])]
    public function show(InfoClientRepository $infoClient, $id): Response
    {
        $client = $infoClient->find($id);
        $user = $this->getUser($id);
        return $this->render('info_client/show.html.twig', [
            'info_client' => $client,
            'user'=>$user->getUserIdentifier(),

        ]);
    }

    #[Route('/{id}/edit', name: 'app_info_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InfoClient $infoClient, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(InfoClientType::class, $infoClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_info_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('info_client/edit.html.twig', [
            'info_client' => $infoClient,
            'form' => $form,
            'user'=>$user->getUserIdentifier()

        ]);
    }

    #[Route('/delete/{id}', name: 'app_info_client_delete', methods: ['POST'])]
    public function delete(Request $request, InfoClient $infoClient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$infoClient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($infoClient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_info_client_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/mesColab', name:'mesColab', methods:['GET'])]
    public function index1(InfoClientRepository $infoClientRepository, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        $clients = $entityManager->getRepository(InfoClient::class)->findBy([
            'id_user' =>$user,
        ]);

        return $this->render('info_client/index.html.twig', [
            'info_clients' => $clients,
            'user'=>$user->getUserIdentifier(),

        ]);

    }


}
