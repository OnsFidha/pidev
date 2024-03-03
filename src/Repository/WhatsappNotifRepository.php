<?php

namespace App\Repository;

use App\Entity\WhatsappNotif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WhatsappNotif>
 *
 * @method WhatsappNotif|null find($id, $lockMode = null, $lockVersion = null)
 * @method WhatsappNotif|null findOneBy(array $criteria, array $orderBy = null)
 * @method WhatsappNotif[]    findAll()
 * @method WhatsappNotif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WhatsappNotifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WhatsappNotif::class);
    }

//    /**
//     * @return WhatsappNotif[] Returns an array of WhatsappNotif objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WhatsappNotif
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
