<?php

namespace App\Controller;

use App\Entity\FichierBilan;
use App\Form\FichierBilanType;
use App\Repository\FichierBilanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fichier/bilan')]
class FichierBilanController extends AbstractController
{
    #[Route('/', name: 'app_fichier_bilan_index', methods: ['GET'])]
    public function index(FichierBilanRepository $fichierBilanRepository): Response
    {
        return $this->render('fichier_bilan/index.html.twig', [
            'fichier_bilans' => $fichierBilanRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fichier_bilan_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fichierBilan = new FichierBilan();
        $form = $this->createForm(FichierBilanType::class, $fichierBilan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fichierBilan);
            $entityManager->flush();

            return $this->redirectToRoute('app_fichier_bilan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fichier_bilan/new.html.twig', [
            'fichier_bilan' => $fichierBilan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fichier_bilan_show', methods: ['GET'])]
    public function show(FichierBilan $fichierBilan): Response
    {
        return $this->render('fichier_bilan/show.html.twig', [
            'fichier_bilan' => $fichierBilan,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fichier_bilan_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FichierBilan $fichierBilan, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FichierBilanType::class, $fichierBilan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fichier_bilan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fichier_bilan/edit.html.twig', [
            'fichier_bilan' => $fichierBilan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fichier_bilan_delete', methods: ['POST'])]
    public function delete(Request $request, FichierBilan $fichierBilan, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fichierBilan->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fichierBilan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fichier_bilan_index', [], Response::HTTP_SEE_OTHER);
    }
}
