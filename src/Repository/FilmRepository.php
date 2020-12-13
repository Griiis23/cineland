<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    public function findEntreAnnees($annee1,$annee2)
    {
        $date1 = \DateTime::createFromFormat("Y-m-d", "$annee1-01-01");
        $date2 = \DateTime::createFromFormat("Y-m-d", "$annee2-12-31");

        return $this->createQueryBuilder('f')
            ->andWhere('f.dateSortie >= :val1')
            ->andWhere('f.dateSortie <= :val2')
            ->setParameter('val1', $date1)
            ->setParameter('val2', $date2)
            ->getQuery()
            ->getResult();
    }

    public function findAnterieureDate($date)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.dateSortie <= :val')
            ->setParameter('val', $date)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Film[] Returns an array of Film objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Film
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
