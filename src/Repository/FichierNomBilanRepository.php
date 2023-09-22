<?php

namespace App\Repository;

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
