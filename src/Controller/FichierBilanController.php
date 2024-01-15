<?php

namespace App\Controller;
use App\Entity\Annee;
use App\Entity\Fichier;
use App\Entity\FichierBilan;
use App\Entity\FichierDemande;
use App\Entity\FichierNomBilan;
use App\Entity\InfoClient;
use App\Form\FichierBilanType;
use App\Form\FichierDemandeType;
use App\Repository\AnneeRepository;
use App\Repository\FichierBilanRepository;
use App\Repository\FichierDemandeRepository;
use App\Repository\FichierNomBilanRepository;
use App\Repository\FichierRepository;
use App\Repository\InfoClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


#[Route('/fichier/bilan')]
class FichierBilanController extends AbstractController
{
    #[Route('/', name: 'app_fichier_bilan_index', methods: ['GET'])]
    public function index(InfoClientRepository $infoClientRepository,EntityManagerInterface $entityManager,FichierBilanRepository $fichierBilanRepository): Response
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

    #[Route('/mesFichiersBilan/{idClient}/{id}', name:'mesFichiersBilan', methods:['GET'])]
    public function indexFichier(string $idClient,$id, FichierBilanRepository $fichierBilanRepository, AnneeRepository $anneeRepository, EntityManagerInterface $entityManager, InfoClientRepository $infoClientRepository): Response
    {
        $client = $infoClientRepository->find($id);
//        $nomClient = $client->getNom();
//
        // Vous pouvez utiliser $client->getNomSociete() si nécessaire
        $user = $this->getUser();
        if ($user->getRoles()[0]=="ROLE_ADMIN")
        {
            $bilan = $fichierBilanRepository->findBy([
                'id_info_client'=>$idClient,
                'id_annee'=>$id,
            ]);
        }
        else
        {
            $bilan = $fichierBilanRepository->findBy([
                'id_info_client'=>$idClient,
                'id_annee'=>$id,
                'status'=>1
            ]);
        }

        $annee = $anneeRepository->findOneBy([
            'id'=>$id
        ]);


        return $this->render('fichier_bilan/listBilanParAnnee.html.twig', [
            'user' => $user->getUserIdentifier(),
            'annee'=>$annee,
            'bilans' => $bilan,
            'clients'=> $client
        ]);
    }

    #[Route('/fichierAnnee{id}', name: 'app_fichier_bilan_annee', methods: ['GET'])]
    public function fichierAnnee($id, AnneeRepository $anneeRepository,EntityManagerInterface $entityManager,FichierBilanRepository $fichierBilanRepository): Response
    {
        $user=$this->getUser();
        $client = $entityManager->getRepository(InfoClient::class)->findAll();
        $annee = $anneeRepository->find($id);
        $fichier = $entityManager->getRepository(FichierNomBilan::class)->findAll();
        $fichierBilan = $entityManager->getRepository(FichierBilan::class)->findAll();

        return $this->render('fichier_demande/unFichier.twig', [
            'fichier_nom_bilans' => $fichierBilanRepository->findAll(),
            'user'=>$user->getUserIdentifier(),
            'clients' => $client,
            'fichiers' => $fichier,
            'fichierBilans'=>$fichierBilan,
            'annees'=>$annee

        ]);
    }
//    #[Route('/mesFichiers/{id}', name:'mesFichiers', methods:['GET', 'POST'])]
//    public function indexFichierBilan($id,FichierBilanRepository $fichierBilanRepository, FichierNomBilanRepository $fichierNomBilanRepository, Request $request, EntityManagerInterface $entityManager, InfoClientRepository $infoClientRepository): Response
//    {
//
//        $client = $infoClientRepository->find($id);
//        $societeClient = $client->getNomSociete();
//        $user = $this->getUser();
//        $annee = $entityManager->getRepository(Annee::class)->findAll();
//        $fichier = $entityManager->getRepository(FichierNomBilan::class)->findAll();
//        $bilan = $entityManager->getRepository(FichierBilanRepository::class)->findBy([
//            'id_info_client' => $client,
//        ]);
//
//        $fichierBilan = new FichierBilan();
//        $form = $this->createForm(FichierBilanType::class, $fichierBilan);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            dd("ok");
//            $uploadedFile = $form->get('nom_fichier_bilan')->getData();
//            // Il s'agit de l'id du client
//            $idClient = $form->get('id_info_client')->getData()->getid();
//            $idClient = $infoClientRepository->find($idClient);
//
//            //Il s'agit de l'id du fichier demander pour tout les clients
//            $idNomFichier= $form->get('id_fichier_bilan')->getData()->getId();
//            $idNomFichier = $fichierRepository->find($idNomFichier);
//            $nomOriginal = $form->get('nom_fichier_bilan')->getData()->getClientOriginalName();
//            // le chemin ou le fichier est inserer
//            $nomOriginal = $uploadedFile->getClientOriginalName();
//            $destinationDirectory = 'D:\XAMPP\htdocs\WEB\INTRANET\InsererFichier\public\fichier';
//            $newFilename = $nomOriginal;
//            $uploadedFile->move($destinationDirectory, $newFilename);
//            $fichierBilan->setIdUser($user);
//            $fichierBilan->setNomFichierBilan($newFilename);
//            $fichierBilan->setIdInfoClient($idClient);
//            $fichierBilan->setIdFichierBilan($idNomFichier);
//            $entityManager->persist($fichierBilan);
//            $entityManager->flush();
//
//            $fichierBilanRepository->save($fichierBilan, true);
//            return $this->redirectToRoute('mesFichiers', [], Response::HTTP_SEE_OTHER);
//
//        }
//        return $this->render('fichier_bilan/unFichier.html.twig', [
//            'bilans' => $bilan,
//            'user' => $user->getUserIdentifier(),
//            'clients' => $client,
//            'societe' => $societeClient,
//            'fichiers' => $fichier,
//            'annees' => $annee,
//            'form' => $form
//        ]);
//    }



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
            $destinationDirectory = 'C:/Users/benja/projects/php/InsererFichier/public/fichier/';
            $newFilename = $nomOriginal;
            $uploadedFile->move($destinationDirectory, $newFilename);
            $fichierBilan->setIdUser($user);
            $fichierBilan->setNomFichierBilan($newFilename);
            $fichierBilan->setIdInfoClient($idClient);
            $fichierBilan->setIdFichierBilan($idNomFichierBilan);
            $fichierBilan->setStatus(1);
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
            $fichierBilan->setStatus(0);
            $entityManager->persist($fichierBilan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fichier_bilan_index', [], Response::HTTP_SEE_OTHER);
    }
}
