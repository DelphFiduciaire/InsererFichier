<?php

namespace App\DataFixtures;

use App\Entity\Annee;
use App\Entity\Fichier;
use App\Entity\FichierBilan;
use App\Entity\FichierDemande;
use App\Entity\FichierNomBilan;
use App\Entity\InfoClient;
use App\Entity\User;
use App\Repository\InfoClientRepository;
use App\Security\UserAuthenticator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $hasher){

        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0;$i<=10;$i++)
        {
            $user = new User();
            $user ->setEmail("user".$i. "@gmail.com");

            $password = $this->hasher->hashPassword($user,'user'.$i);
            $user->setPassword($password);

            $user->setRoles(['ROLE_COMPTABLE']);

            $manager->persist($user);
            $manager->flush();
        }


        $user = new User();
        $user ->setEmail("orel@admin.com");

        $password = $this->hasher->hashPassword($user,'admin');
        $user->setPassword($password);

        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
        $manager->flush();



        for ($i = 0; $i<=20;$i++)
        {
            $randomUser = rand($user->getId()-11,$user->getId());
            $infosClient = new InfoClient();
            $infosClient->setIdUser($manager->find(User::class,$randomUser));
            $infosClient->setNom("nomClient ".$i);
            $infosClient->setPrenom("prenomClient ".$i);
            $infosClient->setMailPro("client.mail".$i."@email.com");
            $infosClient->setNomSociete("société ".$i);
            $infosClient->setAdresse("rue ".$i);
            $infosClient->setNumPro($i);
            $infosClient->setNum($i);
            $infosClient->setcp($i);
            $infosClient->setVille("ville ".$i);
            $infosClient->setSiret($i);
            $infosClient->setStatus(1);
            $manager->persist($infosClient);
            $manager->flush();
        }
        for ($i = 0; $i<=9;$i++)
        {
           $annee = new Annee();
           $annee->setAnneeBilan("200".$i);
            $manager->persist($annee);
            $manager->flush();
        }
        for ($i = 0; $i<=10;$i++)
        {
            $fichier = new Fichier;
            $fichier->setNomFichier("nom_fichier ".$i);
            $manager->persist($fichier);
            $manager->flush();
        }
        for ($i = 0; $i<=10;$i++)
        {
            $fichierNomBilan = new FichierNomBilan();
            $fichierNomBilan->setFichierBilan("nom_fichier_bilan ".$i);
            $manager->persist($fichierNomBilan);
            $manager->flush();
        }
        /*
         bannie car il faut inséré des fichiers (word, pdf, excel) pour qu'il fonctionne correctement
        for ($i = 0; $i<=20;$i++)
        {
            $randomFichierBilan = rand($fichierNomBilan->getId()-10,$fichierNomBilan->getId());
            $randomInfosClient = rand($infosClient->getId()-20,$infosClient->getId());
            $randomAnnee = rand($annee->getId()-9,$annee->getId());

            $fichierBilan = new FichierBilan();
            $fichierBilan->setIdUser($user);
            $fichierBilan->setIdInfoClient($manager->find(InfoClient::class,$randomInfosClient));
            $fichierBilan->setIdFichierBilan($manager->find(FichierNomBilan::class,$randomFichierBilan));
            $fichierBilan->setIdAnnee($manager->find(Annee::class,$randomAnnee));
            $fichierBilan->setNomFichierBilan("fichier_bilan ". $i);
            $fichierBilan->setVerifBilan(1);
            $fichierBilan->setStatus(1);
            $manager->persist($fichierBilan);
            $manager->flush();
        }
        */
        /*
         bannie car il faut inséré des fichiers (word, pdf, excel) pour qu'il fonctionne correctement
        for ($i = 0; $i<=20;$i++)
        {

            $randomFichier = rand($fichier->getId()-10,$fichier->getId());
            $randomInfosClient = rand($infosClient->getId()-20,$infosClient->getId());
            $fichierDemande = new FichierDemande();
            $fichierDemande->setIdUser($user);
            $fichierDemande->setIdFichier($manager->find(Fichier::class,$randomFichier));
            $fichierDemande->setIdInfoClient($manager->find(InfoClient::class,$randomInfosClient));
            $fichierDemande->setNomFichierDemande("fichier_demande ". $i);
            $fichierDemande->setVerif(1);
            $fichierDemande->setStatus(1);

            $manager->persist($fichierDemande);
            $manager->flush();
        }*/

    }
}
