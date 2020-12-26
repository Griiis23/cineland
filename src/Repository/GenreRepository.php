<?php

namespace App\Repository;

use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Genre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Genre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Genre[]    findAll()
 * @method Genre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    public function findActeur2Films($acteur)
    {
        return $this->createQueryBuilder('g')
            ->join('g.films', 'f')
            ->join('f.acteurs', 'a')
            ->where('a = :acteur')
            ->setParameter(':acteur', $acteur)
            ->groupBy('g')
            ->having('COUNT(g) >= 2')
            ->getQuery()
            ->getResult()
        ;
    }

    public function dureeMoyenne($genre)
    {
        return $this->createQueryBuilder('g')
            ->select('AVG(f.duree)')
            ->join('g.films', 'f')
            ->where('g = :genre')
            ->setParameter(':genre', $genre)
            ->groupBy('g')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Genre[] Returns an array of Genre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Genre
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
