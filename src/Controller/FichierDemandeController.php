<?php

namespace App\Controller;
use App\Entity\FichierDemande;
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
        $test = $fichierDemandeRepository->findAll();

        // dd($fichierDemandeRepository->findAll());
        return $this->render('fichier_demande/index.html.twig', [
            'fichier_demandes' =>   $fichierDemandeRepository->createQueryBuilder('fd')
                ->leftJoin('fd.id_info_client', 'u')
                ->leftJoin('fd.id_fichier', 'f')
                ->addSelect('u')
                ->addSelect('f')
                ->getQuery()
                ->getResult(),
            'user'=>$user->getUserIdentifier()
        ]);

    }


    #[Route('/mesFichiers', name:'mesFichiers', methods:['GET'])]
    public function indexFichier(FichierDemandeRepository $fichierDemandeRepository, EntityManagerInterface $entityManager): Response
    {


        $user = $this->getUser();
        $userId = $user->getId();
//        $test = $fichierDemandeRepository->findAll();

        $query = $fichierDemandeRepository->createQueryBuilder('fd')

            ->leftJoin('fd.id_info_client', 'u')
                ->leftJoin('fd.id_fichier', 'f')
                ->addSelect('u')
                ->addSelect('f')
                ->where('u.id = :userId')
                ->setParameter('userId', $userId)
                ->getQuery();

        $fichier_demandes = $query->getResult();

        return $this->render('fichier_demande/index.html.twig', [
            'fichier_demandes' => $fichier_demandes,
            'user' => $user->getUserIdentifier()
        ]);

//        $user = $this->getUser();
//        $clients = $entityManager->getRepository(FichierDemande::class)->findBy([
//            'fichier' => $fichierDemandeRepository->find($this->getUser())
//        ]);
//
//        return $this->render('fichier_demande/index.html.twig', [
//            'info_clients' => $clients,
//            'fichier' => $fichierDemandeRepository->findAll(),
//            'user'=>$user->getUserIdentifier()

//        $user = $this->getUser();
//        return $this->render('fichier_demande/index.html.twig', [
//            'fichier' => $fichierDemandeRepository->findAll(),
//            'user'=>$user->getUserIdentifier()

    }

    #[Route('/new', name: 'app_fichier_demande_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $entityManager ,Request $request, UserRepository $userrepo, InfoClientRepository $infocrepo , FichierRepository $fichierrepo, FichierDemandeRepository $fichierDemandeRepository): Response
    {
        $user = $this->getUser();
        $fichierDemande = new FichierDemande();
        $form = $this->createForm(FichierDemandeType::class, $fichierDemande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('nom_fichier_demande')->getData();
//            dd($uploadedFile);
            // Il s'agit de l'id du client
            $idClient = $form->get('id_info_client')->getData()->getid();
            $idClient = $infocrepo->find($idClient);
//            dd($idClient);
//            $idClient = $userrepo->find($idClient);
//            dd($idClient);

            //Il s'agit de l'id du fichier demander pour tout les clients
            $idNomFichier= $form->get('id_fichier')->getData()->getId();

            $idNomFichier = $fichierrepo->find($idNomFichier);
//            dd($idNomFichier);
            $nomOriginal = $form->get('nom_fichier_demande')->getData()->getClientOriginalName();
//            dd($nomOriginal);

            // le chemin ou le fichier est inserer
            $destinationDirectory = 'D:\XAMPP\htdocs\WEB\InsererFichier\public\fichier';
            $newFilename = $nomOriginal;
            $uploadedFile->move($destinationDirectory, $newFilename);
            $fichierDemande->setIdUser($user);
            $fichierDemande->setNomFichierDemande($newFilename);
            $fichierDemande->setIdInfoClient($idClient);
            $fichierDemande->setIdFichier($idNomFichier);
//            dd($fichierDemande);
            $entityManager->persist($fichierDemande);
            $entityManager->flush();
            $fichierDemandeRepository->save($fichierDemande, true);
//            return $this->redirectToRoute('app_fichier_demande_index', [], Response::HTTP_SEE_OTHER);

    }

        return $this->renderForm('fichier_demande/new.html.twig', [
            'nom_fichier_demande' => $fichierDemande,
            'form' => $form,
            'user'=>$user->getUserIdentifier()
        ]);
    }

//    #[Route('/view/{id}', name: 'app_fichier_demande_show', methods: ['GET'])]
//    public function show(FichierDemande $fichierDemande): Response
//    {
//        $pathToFile = 'fichierpdf/' . $fichierDemande->getNomFichierDemande();
//        return $this->file($pathToFile);
//    }

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

    #[Route('/{id}', name: 'app_fichier_demande_delete', methods: ['POST'])]
    public function delete(Request $request, FichierDemande $fichierDemande, FichierDemandeRepository $fichierDemandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fichierDemande->getId(), $request->request->get('_token'))) {
            $fichierDemandeRepository->remove($fichierDemande, true);
        }

        return $this->redirectToRoute('app_fichier_demande_index', [], Response::HTTP_SEE_OTHER);
    }







}
