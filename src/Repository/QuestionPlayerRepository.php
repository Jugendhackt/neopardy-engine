<?php

namespace App\Repository;

use App\Entity\QuestionPlayer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method QuestionPlayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionPlayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionPlayer[]    findAll()
 * @method QuestionPlayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionPlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionPlayer::class);
    }

    // /**
    //  * @return QuestionPlayer[] Returns an array of QuestionPlayer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuestionPlayer
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
