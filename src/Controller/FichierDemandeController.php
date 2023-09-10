<?php

namespace App\Controller;

use App\Entity\FichierDemande;
use App\Form\FichierDemandeType;
use App\Repository\FichierDemandeRepository;
use App\Repository\FichierRepository;
use App\Repository\UserRepository;
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
        $user = $this->getUser();
//        $test = $fichierDemandeRepository->findAll();

        return $this->render('fichier_demande/index.html.twig', [
            'fichier_demandes' =>   $fichierDemandeRepository->createQueryBuilder('fd')
                ->leftJoin('fd.id_user', 'u')
                ->leftJoin('fd.id_fichier', 'f')
                ->addSelect('u')
                ->addSelect('f')
                ->getQuery()
                ->getResult(),
            'user'=>$user->getUserIdentifier()
        ]);
    }

    #[Route('/new', name: 'app_fichier_demande_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $entityManager ,Request $request, UserRepository $userrepo, FichierRepository $fichierrepo, FichierDemandeRepository $fichierDemandeRepository): Response
    {
        $user = $this->getUser();
        $fichierDemande = new FichierDemande();
        $form = $this->createForm(FichierDemandeType::class, $fichierDemande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('nom_fichier')->getData();
            // Il s'agit de l'id du client
            $idUser = $form->get('id_user')->getData()->getId();
            $idUser = $userrepo->find($idUser);

            //Il s'agit de l'id du fichier demander pour tout les clients
            $idNomFichier= $form->get('id_fichier')->getData()->getId();
            $idNomFichier = $fichierrepo->find($idNomFichier);

            $nomOriginal = $form->get('nom_fichier')->getData()->getClientOriginalName();

            // le chemin ou le fichier est inserer
            $destinationDirectory = 'D:/XAMPP/htdocs/WEB/DELPH/cms_delph/public/'.'fichier';
            $newFilename = $nomOriginal;
            $uploadedFile->move($destinationDirectory, $newFilename);
            $fichierDemande->setNomFichier($newFilename);
            $fichierDemande->setIdUser($idUser);
            $fichierDemande->setIdFichier($idNomFichier);
            $entityManager->persist($fichierDemande);
            $entityManager->flush();
            $fichierDemandeRepository->save($fichierDemande, true);

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
        $pathToFile = 'fichierpdf/' . $fichierDemande->getNomFichier();
        return $this->file($pathToFile);
    }

    #[Route('/{id}/edit', name: 'app_fichier_demande_edit', methods: ['GET', 'POST'])]
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


    #[Route('/{name}', name: 'app_view_pdf', methods: ['GET'])]
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
