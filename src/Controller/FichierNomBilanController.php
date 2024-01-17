<?php

namespace App\Controller;

use App\Entity\FichierNomBilan;
use App\Form\FichierNomBilanType;
use App\Repository\FichierBilanRepository;
use App\Repository\FichierNomBilanRepository;
use App\Repository\FichierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fichier/nom/bilan')]
class FichierNomBilanController extends AbstractController
{
    #[Route('/', name: 'app_fichier_nom_bilan_index', methods: ['GET'])]
    public function index(FichierNomBilanRepository $fichierNomBilanRepository): Response
    {
        $user= $this->getUser();
        return $this->render('fichier_nom_bilan/index.html.twig', [
            'fichier_nom_bilans' => $fichierNomBilanRepository->findAll(),
            'user'=>$user->getUserIdentifier()
        ]);
    }


    #[Route('/new', name: 'app_fichier_nom_bilan_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user =$this->getUser();
        $fichierNomBilan = new FichierNomBilan();
        $form = $this->createForm(FichierNomBilanType::class, $fichierNomBilan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fichierNomBilan);
            $entityManager->flush();

            return $this->redirectToRoute('app_fichier_nom_bilan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fichier_nom_bilan/new.html.twig', [
            'fichier_nom_bilan' => $fichierNomBilan,
            'form' => $form,
            'user'=>$user->getUserIdentifier()
        ]);
    }

    #[Route('/admin/addBilan', name: 'app_fichierNomBilan_new', methods: ['GET'])]
    public function newBilan( FichierNomBilanRepository $fichierNomBilanRepository,EntityManagerInterface $entityManager): Response
    {
        // jappele la fonction pour ajouter un fichier avec le name en get
        $bilan = $fichierNomBilanRepository->insertFichier($entityManager,$_GET['bilan']);
        return $this->redirectToRoute('app_fichier_nom_bilan_index', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/{id}', name: 'app_fichier_nom_bilan_show', methods: ['GET'])]
    public function show(FichierNomBilan $fichierNomBilan): Response
    {
        return $this->render('fichier_nom_bilan/show.html.twig', [
            'fichier_nom_bilan' => $fichierNomBilan,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fichier_nom_bilan_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FichierNomBilan $fichierNomBilan, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(FichierNomBilanType::class, $fichierNomBilan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fichier_nom_bilan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fichier_nom_bilan/edit.html.twig', [
            'fichier_nom_bilan' => $fichierNomBilan,
            'form' => $form,
            'user'=>$user,
        ]);
    }

    #[Route('/{id}', name: 'app_fichier_nom_bilan_delete', methods: ['POST'])]
    public function delete(Request $request, FichierNomBilan $fichierNomBilan, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fichierNomBilan->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fichierNomBilan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fichier_nom_bilan_index', [], Response::HTTP_SEE_OTHER);
    }
}
