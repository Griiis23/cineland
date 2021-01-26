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

    public function find2Acteurs($acteur1,$acteur2)
    {
        return $this->createQueryBuilder('f')
            ->join('f.acteurs', 'a1')
            ->join('f.acteurs', 'a2')
            ->where('a1 = :acteur1')
            ->andWhere('a2 = :acteur2')
            ->setParameter(':acteur1', $acteur1)
            ->setParameter(':acteur2', $acteur2)
            ->getQuery()
            ->getResult();
    }

    public function findDureeActeur($acteur)
    {
        return $this->createQueryBuilder('f')
            ->select('f.titre','f.duree')
            ->join('f.acteurs', 'a')
            ->where('a = :acteur')
            ->setParameter(':acteur', $acteur)
            ->getQuery()
            ->getScalarResult();
    }

    public function findPartieTitre($partie)
    {
        return $this->createQueryBuilder('f')
            ->where('f.titre LIKE :titre')
            ->setParameter(':titre', '%'.$partie.'%')
            ->getQuery()
            ->getResult();
    }


    public function augmenterAgeMin($acteur,$age): array
    {
        $queryBuilder = $this->createQueryBuilder('f')
                ->select('f.titre')
                ->join("f.acteurs","a")
                ->where('a = :acteur')
                ->setParameter('acteur',$acteur)
                ->getQuery()
                ->getResult();
            foreach ($queryBuilder as $key => $titre) {
                $query = $this->getEntityManager()->createQuery("UPDATE App\Entity\Film f SET f.ageMinimal = f.ageMinimal + :age where f.titre LIKE :titre")
                    ->setParameter('age',$age)
                    ->setParameter('titre',$titre);
                      
                $result = $query->execute();
            } 
        return $queryBuilder;
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
