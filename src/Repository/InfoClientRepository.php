<?php

namespace App\Repository;

use App\Entity\InfoClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Void_;

/**
 * @extends ServiceEntityRepository<InfoClient>
 *
 * @method InfoClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfoClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfoClient[]    findAll()
 * @method InfoClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfoClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfoClient::class);
    }


    public function save(InfoClient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InfoClient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findAllClient(): array
   {
        return $this->createQueryBuilder('c')
            ->andWhere('c.mail_pro != :mailpro')
            ->setParameter('mailpro','pardefaut@email.com')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findClientByUser(int $idUser): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.mail_pro != :mailpro ')
            ->andWhere('c.id_user = :idUser')
            ->setParameter('mailpro','pardefaut@email.com')
            ->setParameter('idUser', $idUser)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return InfoClient[] Returns an array of InfoClient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InfoClient
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
