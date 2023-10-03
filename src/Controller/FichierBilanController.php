<?php

namespace App\Controller;

use App\Entity\Annee;
use App\Entity\Fichier;
use App\Entity\FichierBilan;
use App\Entity\FichierNomBilan;
use App\Entity\InfoClient;
use App\Form\FichierBilanType;
use App\Repository\AnneeRepository;
use App\Repository\FichierBilanRepository;
use App\Repository\FichierNomBilanRepository;
use App\Repository\InfoClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormView;

#[Route('/fichier/bilan')]
class FichierBilanController extends AbstractController
{
    #[Route('/', name: 'app_fichier_bilan_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,FichierBilanRepository $fichierBilanRepository): Response
    {
        $user=$this->getUser();
        $client = $entityManager->getRepository(InfoClient::class)->findAll();
        $fichier = $entityManager->getRepository(FichierNomBilan::class)->findAll();

        return $this->render('fichier_bilan/index.html.twig', [
            'fichier_bilans' => $fichierBilanRepository->findAll(),
            'user'=>$user->getUserIdentifier(),
            'clients' => $client,
            'fichiers' => $fichier

        ]);
    }

//    #[Route('/fichierAnnee', name: 'app_fichier_bilan_annee', methods: ['GET'])]
//    public function fichierAnnee(EntityManagerInterface $entityManager,FichierBilanRepository $fichierBilanRepository): Response
//    {
//        $user=$this->getUser();
//        $client = $entityManager->getRepository(InfoClient::class)->findAll();
//        $fichier = $entityManager->getRepository(FichierNomBilan::class)->findAll();
//        $fichierBilan = $entityManager->getRepository(FichierBilan::class)->findAll();
//
//        return $this->render('fichier_demande/unFichier.html.twig', [
//            'fichier_nom_bilans' => $fichierBilanRepository->findAll(),
//            'user'=>$user->getUserIdentifier(),
//            'clients' => $client,
//            'fichiers' => $fichier,
//            'fichierBilans'=>$fichierBilan
//
//        ]);
//    }

    #[Route('/mesFichiersBilan/{id}/{id_client}', name:'mesFichiersBilan', methods:['GET'])]
    public function indexFichier($id,$id_client,FichierBilanRepository $fichierBilanRepository,AnneeRepository $anneeRepository, EntityManagerInterface $entityManager, InfoClientRepository $infoClientRepository): Response
    {
        $client = $infoClientRepository->find($id_client);
        $anneBilan = $anneeRepository->find($id);
        $user = $this->getUser();
//        $annee = $entityManager->getRepository(Annee::class)->findAll();
        $bilan = $entityManager->getRepository(FichierNomBilan::class)->findAll();
        $annee = $entityManager->getRepository(Annee::class)->findBy([
            'annee_bilan'=> $anneBilan,
        ]);

        $nomClient = $entityManager->getRepository(InfoClient::class)->findBy([
            'nom'=> $client,
        ]);
        return $this->render('fichier_bilan/fichierAnnee.twig', [
            'user' => $user->getUserIdentifier(),
            'nomClients' => $nomClient,
            'bilans'=>$bilan,
            'annees'=>$annee
        ]);
    }

    #[Route('/new', name: 'app_fichier_bilan_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, InfoClientRepository $infoClientRepository, FichierBilanRepository $fichierBilanRepository, FichierNomBilanRepository $fichierNomBilanRepository): Response
    {
        $user = $this->getUser();
        $fichierBilan = new FichierBilan();
        $form = $this->createForm(FichierBilanType::class, $fichierBilan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('nom_fichier_bilan')->getData();
            $idClient = $form->get('id_info_client')->getData()->getid();
            $idClient = $infoClientRepository->find($idClient);

            $idNomFichierBilan = $form->get('id_fichier_bilan')->getData()->getId();
            $idNomFichierBilan = $fichierNomBilanRepository->find($idNomFichierBilan);
            $nomOriginal = $form->get('nom_fichier_bilan')->getData()->getClientOriginalName();

            $nomOriginal = $uploadedFile->getClientOriginalName();
            $destinationDirectory = 'D:\XAMPP\htdocs\WEB\InsererFichier\public\fichier';
            $newFilename = $nomOriginal;
            $uploadedFile->move($destinationDirectory, $newFilename);
            $fichierBilan->setIdUser($user);
            $fichierBilan->setNomFichierBilan($newFilename);
            $fichierBilan->setIdInfoClient($idClient);
            $fichierBilan->setIdFichierBilan($idNomFichierBilan);
            $entityManager->persist($fichierBilan);
            $entityManager->flush();
            $fichierBilanRepository->save($fichierBilan, true);

            return $this->redirectToRoute('app_fichier_bilan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fichier_bilan/new.html.twig', [
            'fichier_bilan' => $fichierBilan,
            'form' => $form,
            'user' => $user->getUserIdentifier()
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
