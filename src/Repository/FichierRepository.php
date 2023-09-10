<?php

namespace App\Repository;

use App\Entity\Fichier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fichier>
 *
 * @method Fichier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fichier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fichier[]    findAll()
 * @method Fichier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fichier::class);
    }


    public function save(Fichier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fichier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    // fonction qui met denregistrer le nom d'un fichier appele dans le controller fichier
    public function insert2(EntityManagerInterface $em,$d)
    {
        $sql = "INSERT INTO `fichier`(`nom_fichier`) VALUES ('$d')";

        $stmt = $em->getConnection()->prepare($sql);
        $resul = $stmt->executeQuery()->fetchAllAssociative();

        return $resul;
    }


    public function deleteFile(EntityManagerInterface $em, $fichier)
    {
        $sql = "DELETE FROM `fichier` WHERE nom_fichier = '".$fichier."'";

        $stmt = $em->getConnection()->prepare($sql);
        $resul = $stmt->executeQuery()->fetchAllAssociative();
        return $resul;
    }

//    /**
//     * @return Fichier[] Returns an array of Fichier objects
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

//    public function findOneBySomeField($value): ?Fichier
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
