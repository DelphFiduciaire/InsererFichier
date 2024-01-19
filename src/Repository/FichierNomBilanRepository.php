<?php

namespace App\Repository;

use App\Entity\FichierBilan;
use App\Entity\FichierNomBilan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FichierNomBilan>
 *
 * @method FichierNomBilan|null find($id, $lockMode = null, $lockVersion = null)
 * @method FichierNomBilan|null findOneBy(array $criteria, array $orderBy = null)
 * @method FichierNomBilan[]    findAll()
 * @method FichierNomBilan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichierNomBilanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FichierNomBilan::class);
    }


    public function insertFichier(EntityManagerInterface $em,$d)
    {
        $sql = "INSERT INTO `fichier_nom_bilan`(`fichier_bilan`) VALUES ('$d')";

        $stmt = $em->getConnection()->prepare($sql);
        $resul = $stmt->executeQuery()->fetchAllAssociative();

        return $resul;
    }
    public function findFichiersBilansSansDemandeClient($clientId)
    {
        $conn = $this->getEntityManager()->getConnection();
        //sélectionne tous les id fichiers qui n'ont pas été utilisé par le user choisie
        $sql = 'SELECT fichier_nom_bilan.id   FROM fichier_nom_bilan
         WHERE fichier_nom_bilan.id NOT IN (SELECT fichier_bilan.id_fichier_bilan_id
         FROM fichier_bilan
         WHERE fichier_bilan.id_info_client_id = :clientId
         AND status = 1)';

        $resultSet = $conn->executeQuery($sql, ['clientId' => $clientId]);

        //filsIds retourne une array d'array
        $fileIds = $resultSet->fetchAllAssociative();

        // Récupérer les objets Fichier correspondants
        $fichiers = [];
        //transforme la liste d'array de la requête pour la transformé en array d'objet
        foreach ($fileIds as $fileId) {
            $fichier = $this->getEntityManager()->getRepository(FichierNomBilan::class)->find($fileId['id']);
            if ($fichier) {
                $fichiers[] = $fichier;
            }
        }
        return $fichiers;
    }


//    /**
//     * @return FichierNomBilan[] Returns an array of FichierNomBilan objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FichierNomBilan
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
