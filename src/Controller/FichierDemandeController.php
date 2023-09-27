<?php

namespace App\Controller;
use App\Entity\Fichier;
use App\Entity\FichierDemande;
use App\Entity\InfoClient;
use App\Form\FichierDemandeType;
use App\Repository\FichierDemandeRepository;
use App\Repository\InfoClientRepository;
use App\Repository\FichierRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;



#[Route('/fichier/demande')]
class FichierDemandeController extends AbstractController
{



    #[Route('/liste', name: 'app_fichier_demande_index', methods: ['GET'])]
    public function index(FichierDemandeRepository $fichierDemandeRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $client = $entityManager->getRepository(InfoClient::class)->findAll();
        $fichier = $entityManager->getRepository(Fichier::class)->findAll();
//        dd($fichierDemandeRepository->findAll());
        return $this->render('fichier_demande/index.html.twig', [
            'fichiers_demandes' => $fichierDemandeRepository->findAll(),
            'clients' => $client,
            'fichiers' => $fichier,
            'user' => $user->getUserIdentifier(),
        ]);

    }


    #[Route('/mesFichiers/{id}', name: 'mesFichiers', methods: ['GET'])]
    public function indexFichier($id, FichierDemandeRepository $fichierDemandeRepository, EntityManagerInterface $entityManager, InfoClientRepository $infoClientRepository, Request $request): Response
    {
        $user = $this->getUser();

        // Créez le formulaire avec la classe FichierDemandeType et assurez-vous de passer la requête ($request) en paramètre.
        $fichierDemande = new FichierDemande();
        $form = $this->createForm(FichierDemandeType::class, $fichierDemande);
        $form->handleRequest($request);
        $fichier_nom = $entityManager->getRepository(Fichier::class)->findAll();


        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('nom_fichier_demande')->getData();

            // Il n'est pas nécessaire de rechercher l'ID du client ici, car vous l'avez déjà dans la variable $id.
            $idClient = $infoClientRepository->find($id);

            $idNomFichier = $form->get('id_fichier')->getData()->getId();
            $idNomFichier = $fichierDemandeRepository->find($idNomFichier);


            // Vous avez déjà défini $nomOriginal deux fois, vous pouvez supprimer la première occurrence.
            $nomOriginal = $uploadedFile->getClientOriginalName();

            $destinationDirectory = 'D:\XAMPP\htdocs\WEB\InsererFichier\public\fichier';
            $newFilename = $nomOriginal;
            $uploadedFile->move($destinationDirectory, $newFilename);

            $fichierDemande->setIdUser($user);
            $fichierDemande->setNomFichierDemande($newFilename);
            $fichierDemande->setIdInfoClient($idClient);
            $fichierDemande->setIdFichier($idNomFichier);

            $entityManager->persist($fichierDemande);
            $entityManager->flush();

            // Si vous utilisez déjà la méthode save de votre repository, inutile d'appeler persist et flush ici.
            // $fichierDemandeRepository->save($fichierDemande, true);

            return $this->redirectToRoute('app_fichier_demande_index', [], Response::HTTP_SEE_OTHER);
        }

        // Vous devez déplacer cette partie de code avant le "return" précédent, sinon elle ne sera jamais exécutée.
        $client = $infoClientRepository->find($id);
        $nomClient = $client->getNom();
        $prenomClient = $client->getPrenom();

        $fichiers = $fichierDemandeRepository->findBy(['id_info_client' => $client]);

        return $this->render('fichier_demande/unFichier.html.twig', [
            'fichier_demandes' => $fichiers,
            'user' => $user->getUserIdentifier(),
            'nomClient' => $nomClient,
            'prenomClient' => $prenomClient,
            'form' => $form,
            'fichiers' => $fichier_nom,
            'fichiers_nom_demande'=>$fichierDemande
        ]);
    }


    #[Route('/new', name: 'app_fichier_demande_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $entityManager ,Request $request, InfoClientRepository $infoClientRepository , FichierRepository $fichierRepository, FichierDemandeRepository $fichierDemandeRepository): Response
    {
        $user = $this->getUser();
        $fichierDemande = new FichierDemande();
        $form = $this->createForm(FichierDemandeType::class, $fichierDemande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('nom_fichier_demande')->getData();
            // Il s'agit de l'id du client
            $idClient = $form->get('id_info_client')->getData()->getid();
            $idClient = $infoClientRepository->find($idClient);

            //Il s'agit de l'id du fichier demander pour tout les clients
            $idNomFichier= $form->get('id_fichier')->getData()->getId();
            $idNomFichier = $fichierRepository->find($idNomFichier);
            $nomOriginal = $form->get('nom_fichier_demande')->getData()->getClientOriginalName();
            // le chemin ou le fichier est inserer
            $nomOriginal = $uploadedFile->getClientOriginalName();
            $destinationDirectory = 'D:\XAMPP\htdocs\WEB\InsererFichier\public\fichier';
            $newFilename = $nomOriginal;
            $uploadedFile->move($destinationDirectory, $newFilename);
            $fichierDemande->setIdUser($user);
            $fichierDemande->setNomFichierDemande($newFilename);
            $fichierDemande->setIdInfoClient($idClient);
            $fichierDemande->setIdFichier($idNomFichier);
            $entityManager->persist($fichierDemande);
            $entityManager->flush();

            $fichierDemandeRepository->save($fichierDemande, true);
            return $this->redirectToRoute('app_fichier_demande_index', [], Response::HTTP_SEE_OTHER);

    }

        return $this->renderForm('fichier_demande/new.html.twig', [
            'nom_fichier_demande' => $fichierDemande,
            'form' => $form,
            'user'=>$user->getUserIdentifier()
        ]);
    }



    #[Route('/edit/{id}', name: 'app_fichier_demande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FichierDemande $fichierDemande, FichierDemandeRepository $fichierDemandeRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(FichierDemandeType::class, $fichierDemande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichierDemandeRepository->save($fichierDemande, true);
//            $entityManager->flush();

            return $this->redirectToRoute('app_fichier_demande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fichier_demande/edit.html.twig', [
            'fichier_demande' => $fichierDemande,
            'form' => $form,
            'user'=>$user->getUserIdentifier()
        ]);
    }


    #[Route('/view/{name}', name: 'app_view_pdf', methods: ['GET'])]
    public function view_pdf($name)
    {
        $projectRoot = $this->getParameter('kernel.project_dir');
        $filename = $name;

        $filePath = str_replace('\\', '/', $projectRoot) . '/public/fichier/' . $filename;

        return $this->file($filePath, null, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    #[Route('/{id}', name: 'app_fichier_demande_delete', methods: ['GET'])]
    public function delete(Request $request, FichierDemande $fichierDemande, FichierDemandeRepository $fichierDemandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fichierDemande->getId(), $request->request->get('_token'))) {
            $fichierDemandeRepository->remove($fichierDemande, true);
        }

        return $this->redirectToRoute('app_fichier_demande_index', [], Response::HTTP_SEE_OTHER);
    }







}
