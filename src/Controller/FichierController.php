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
    #[Route('', name: 'app_fichier_index', methods: ['GET'])]
    public function index(FichierRepository $fichierRepository): Response
    {
        $user =$this->getUser();
        return $this->render('fichier/index.html.twig', [
            'fichiers' => $fichierRepository->findAll(),
            'user'=>$user->getUserIdentifier()

        ]);
    }

    #[Route('/admin/add', name: 'app_fichier_new1', methods: ['GET'])]
    public function new1( FichierRepository $fichierRepository,EntityManagerInterface $entityManager): Response
    {
        // jappele la fonction pour ajouter un fichier avec le name en get
        $test = $fichierRepository->insert2($entityManager,$_GET['test']);
        return $this->redirectToRoute('app_fichier_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/nouveau_fichier', name: 'app_fichier_new', methods: ['GET'])]
    public function new(Request $request, FichierRepository $fichierRepository): Response
    {
        $user = $this->getUser();
        $fichier = new Fichier();
        $form = $this->createForm(FichierType::class, $fichier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichierRepository->save($fichier, true);

            return $this->redirectToRoute('app_fichier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fichier/new.html.twig', [
            'fichier' => $fichier,
            'form' => $form,
            'user'=>$user->getUserIdentifier(),
        ]);
    }


    #[Route('/{id}', name: 'app_fichier_show', methods: ['GET'])]
    public function show(Fichier $fichier): Response
    {
        $user=$this->getUser();
        return $this->render('fichier/show.html.twig', [
            'fichier' => $fichier,
            'user'=>$user->getUserIdentifier(),

        ]);
    }

    #[Route('/{id}/edit', name: 'app_fichier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fichier $fichier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FichierType::class, $fichier);
        $form->handleRequest($request);
        $user=$this->getUser();


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fichier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fichier/edit.html.twig', [
            'fichier' => $fichier,
            'form' => $form,
            'user'=>$user->getUserIdentifier()

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

    #[Route('/delete/{id}', name: 'app_fichier_delete_file', methods: ['GET'])]
    public function deleteFile( FichierRepository $fichierRepository,Fichier $fichier,Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $fichier->getId(), $request->request->get('_token'))) {
            $fichierRepository->deleteFile($fichier, true);
        }

        return $this->redirectToRoute('app_fichier_index', [], Response::HTTP_SEE_OTHER);
    }
}
