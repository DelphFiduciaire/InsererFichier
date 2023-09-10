<?php

namespace App\Repository;

use App\Entity\FichierDemande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FichierDemande>
 *
 * @method FichierDemande|null find($id, $lockMode = null, $lockVersion = null)
 * @method FichierDemande|null findOneBy(array $criteria, array $orderBy = null)
 * @method FichierDemande[]    findAll()
 * @method FichierDemande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichierDemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FichierDemande::class);
    }

//    /**
//     * @return FichierDemande[] Returns an array of FichierDemande objects
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

//    public function findOneBySomeField($value): ?FichierDemande
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
