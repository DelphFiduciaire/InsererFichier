<?php

namespace App\Controller;

use App\Entity\FichierDemande;
use App\Form\FichierDemandeType;
use App\Repository\FichierDemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fichier/demande')]
class FichierDemandeController extends AbstractController
{
    #[Route('/', name: 'app_fichier_demande_index', methods: ['GET'])]
    public function index(FichierDemandeRepository $fichierDemandeRepository): Response
    {
        return $this->render('fichier_demande/index.html.twig', [
            'fichier_demandes' => $fichierDemandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fichier_demande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fichierDemande = new FichierDemande();
        $form = $this->createForm(FichierDemandeType::class, $fichierDemande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fichierDemande);
            $entityManager->flush();

            return $this->redirectToRoute('app_fichier_demande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fichier_demande/new.html.twig', [
            'fichier_demande' => $fichierDemande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fichier_demande_show', methods: ['GET'])]
    public function show(FichierDemande $fichierDemande): Response
    {
        return $this->render('fichier_demande/show.html.twig', [
            'fichier_demande' => $fichierDemande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fichier_demande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FichierDemande $fichierDemande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FichierDemandeType::class, $fichierDemande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fichier_demande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fichier_demande/edit.html.twig', [
            'fichier_demande' => $fichierDemande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fichier_demande_delete', methods: ['POST'])]
    public function delete(Request $request, FichierDemande $fichierDemande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fichierDemande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fichierDemande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fichier_demande_index', [], Response::HTTP_SEE_OTHER);
    }
}
