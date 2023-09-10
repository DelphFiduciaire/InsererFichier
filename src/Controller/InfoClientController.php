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
        return $this->render('info_client/index.html.twig', [
            'info_clients' => $infoClientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_info_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $infoClient = new InfoClient();
        $form = $this->createForm(InfoClientType::class, $infoClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($infoClient);
            $entityManager->flush();

            return $this->redirectToRoute('app_info_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('info_client/new.html.twig', [
            'info_client' => $infoClient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_info_client_show', methods: ['GET'])]
    public function show(InfoClient $infoClient): Response
    {
        return $this->render('info_client/show.html.twig', [
            'info_client' => $infoClient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_info_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InfoClient $infoClient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InfoClientType::class, $infoClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_info_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('info_client/edit.html.twig', [
            'info_client' => $infoClient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_info_client_delete', methods: ['POST'])]
    public function delete(Request $request, InfoClient $infoClient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$infoClient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($infoClient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_info_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
