<?php

namespace App\Controller;

use App\Entity\Annee;
use App\Form\AnneeType;
use App\Repository\AnneeRepository;
use App\Repository\FichierNomBilanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/annee')]
class AnneeController extends AbstractController
{
    #[Route('/', name: 'app_annee_index', methods: ['GET'])]
    public function index(AnneeRepository $anneeRepository): Response
    {
        $user =$this->getUser();
        return $this->render('annee/index.html.twig', [
            'annees' => $anneeRepository->findAll(),
            'user'=>$user->getUserIdentifier()
        ]);
    }


    #[Route('/admin/addAnnee', name: 'app_AnneeBilan_new', methods: ['GET'])]
    public function newAnnee( AnneeRepository $anneeRepository,EntityManagerInterface $entityManager): Response
    {
        // jappele la fonction pour ajouter un fichier avec le name en get
        $annee = $anneeRepository->insertAnnee($entityManager, $_GET['annee']);
        return $this->redirectToRoute('app_annee_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/new', name: 'app_annee_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $annee = new Annee();
        $form = $this->createForm(AnneeType::class, $annee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($annee);
            $entityManager->flush();

            return $this->redirectToRoute('app_annee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annee/new.html.twig', [
            'annee' => $annee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annee_show', methods: ['GET'])]
    public function show(Annee $annee): Response
    {
        return $this->render('annee/show.html.twig', [
            'annee' => $annee,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_annee_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annee $annee, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnneeType::class, $annee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_annee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annee/edit.html.twig', [
            'annee' => $annee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annee_delete', methods: ['POST'])]
    public function delete(Request $request, Annee $annee, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annee->getId(), $request->request->get('_token'))) {
            $entityManager->remove($annee);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_annee_index', [], Response::HTTP_SEE_OTHER);
    }
}
