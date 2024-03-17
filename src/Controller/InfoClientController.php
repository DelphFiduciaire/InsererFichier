<?php

namespace App\Controller;

use App\Entity\FichierBilan;
use App\Entity\FichierDemande;
use App\Entity\InfoClient;
use App\Form\InfoClientType;
use App\Repository\InfoClientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/info/client')]
class InfoClientController extends AbstractController
{
    #[Route('/', name: 'app_info_client_index', methods: ['GET'])]
    public function index(InfoClientRepository $infoClientRepository): Response
    {
        $user = $this->getUser();

            return $this->render('info_client/index.html.twig', [
                //méthode créer par moi même dans infoClientRepository pour virer le compte par defaut
                'info_clients' => $infoClientRepository->findAllClient(),
                'user'=>$user->getUserIdentifier()
            ]);


    }
    #[IsGranted('ROLE_ADMIN', statusCode: 404, message: 'Access Denied.')]
    #[Route('/supprimer', name: 'app_info_client_index_supprimer', methods: ['GET'])]
    public function indexSupprimer(InfoClientRepository $infoClientRepository): Response
    {
        $user = $this->getUser();
        if($user->getRoles()[0] == "ROLE_ADMIN")
        {
            return $this->render('info_client/index_supprimer.html.twig', [
                //méthode créer par moi même dans infoClientRepository pour virer le compte par defaut
                'info_clients' => $infoClientRepository->findBy(['status'=>0]),
                'user'=>$user->getUserIdentifier()
            ]);
        }
        return $this->redirectToRoute('app_info_client_index',[], Response::HTTP_SEE_OTHER);
    }

    #[Route('/new', name: 'app_info_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, InfoClientRepository $infoClientRepository): Response
    {
        $user = $this->getUser();
        $infoClient = new InfoClient();
        $form = $this->createForm(InfoClientType::class, $infoClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $infoClient->setStatus(1);
            $infoClientRepository->save($infoClient, true);

//            $entityManager->persist($infoClient);
//            $entityManager->flush();

            return $this->redirectToRoute('app_info_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('info_client/new.html.twig', [
            'info_client' => $infoClient,
            'form' => $form,
            'user' => $user->getUserIdentifier(),

        ]);
    }

    #[Route('/show/{id}', name: 'app_info_client_show', methods: ['GET'])]
    public function show(InfoClientRepository $infoClient, $id): Response
    {
        $clients = $infoClient->findBy(['id_user' => $id]);
        $user = $this->getUser($id);
        return $this->render('info_client/show.html.twig', [
            'clients' => $clients,
            'user'=>$user->getUserIdentifier(),

        ]);
    }

    #[Route('/{id}/edit', name: 'app_info_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InfoClient $infoClient, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(InfoClientType::class, $infoClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_info_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('info_client/edit.html.twig', [
            'info_client' => $infoClient,
            'form' => $form,
            'user'=>$user->getUserIdentifier()

        ]);
    }

    #[Route('/delete/{id}', name: 'app_info_client_delete', methods:['POST'])]
    public function delete(Request $request, InfoClient $infoClient, EntityManagerInterface $entityManager,InfoClientRepository $infoClientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$infoClient->getId(), $request->request->get('_token'))) { //|| $this->isCsrfTokenValid('delete',$request->request->get('_token'))


            //permet de récupéré les fichiers de bilan d'un client et les changé sur le par défaut qui a l'id 2
            $fichierDemandeRepository = $entityManager->getRepository(FichierDemande::class);
            $lesFichiers  = $fichierDemandeRepository->findBy(['id_info_client' => $infoClient->getId()]);
            foreach ($lesFichiers as $unFichier)
            {
                $fichierDemander = $unFichier;
                $fichierDemander->setStatus(0);
                $entityManager->persist($fichierDemander);
                $entityManager->flush();
            }

            //permet de récupéré les fichiers de bilan d'un client et les changé sur le par défaut qui a l'id 2
            $fichierBilanRepository = $entityManager->getRepository(FichierBilan::class);
            $lesFichiersBilan  = $fichierBilanRepository->findBy(['id_info_client' => $infoClient->getId()]);
            foreach ($lesFichiersBilan as $unFichier)
            {
                $fichierBilan = $unFichier;
                $fichierBilan->setStatus(0);
                $entityManager->persist($fichierBilan);
                $entityManager->flush();
            }
            //maintenant on peut le supprimé car il n'a plus de clés étrangères
            $infoClient->setStatus(0);
            $entityManager->persist($infoClient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_info_client_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/mesColab', name:'mesColab', methods:['GET'])]
    public function index1(UserRepository $userRepository, InfoClientRepository $infoClientRepository, EntityManagerInterface $entityManager): Response
    {


            $user = $this->getUser();
            $userObject = $userRepository->findOneBy(['email'=>$user->getUserIdentifier()]);
            return $this->render('info_client/index.html.twig', [
                //méthode créer par moi qui va chercher tous les client d'un user sans le par défaut
                'info_clients' => $infoClientRepository->findClientByUser($userObject->getId()),
                'user'=>$user->getUserIdentifier(),]);


    }


}
