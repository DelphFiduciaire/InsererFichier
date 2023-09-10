<?php

namespace App\Controller;

use App\Entity\Fichier;
use App\Form\FichierType;
use App\Repository\FichierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fichier')]
class FichierController extends AbstractController
{
    #[Route('/', name: 'app_fichier_index', methods: ['GET'])]
    public function index(FichierRepository $fichierRepository): Response
    {
        return $this->render('fichier/index.html.twig', [
            'fichiers' => $fichierRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fichier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fichier = new Fichier();
        $form = $this->createForm(FichierType::class, $fichier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fichier);
            $entityManager->flush();

            return $this->redirectToRoute('app_fichier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fichier/new.html.twig', [
            'fichier' => $fichier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fichier_show', methods: ['GET'])]
    public function show(Fichier $fichier): Response
    {
        return $this->render('fichier/show.html.twig', [
            'fichier' => $fichier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fichier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fichier $fichier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FichierType::class, $fichier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fichier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fichier/edit.html.twig', [
            'fichier' => $fichier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fichier_delete', methods: ['POST'])]
    public function delete(Request $request, Fichier $fichier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fichier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fichier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fichier_index', [], Response::HTTP_SEE_OTHER);
    }
}
