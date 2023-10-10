<?php

namespace App\Repository;

use App\Entity\FichierBilan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FichierBilan>
 *
 * @method FichierBilan|null find($id, $lockMode = null, $lockVersion = null)
 * @method FichierBilan|null findOneBy(array $criteria, array $orderBy = null)
 * @method FichierBilan[]    findAll()
 * @method FichierBilan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichierBilanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FichierBilan::class);
    }


    public function save(FichierBilan $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FichierBilan[] Returns an array of FichierBilan objects
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

//    public function findOneBySomeField($value): ?FichierBilan
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
