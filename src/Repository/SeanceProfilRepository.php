<?php

namespace App\Repository;

use App\Entity\SeanceProfil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SeanceProfil>
 *
 * @method SeanceProfil|null find($id, $lockMode = null, $lockVersion = null)
 * @method SeanceProfil|null findOneBy(array $criteria, array $orderBy = null)
 * @method SeanceProfil[]    findAll()
 * @method SeanceProfil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeanceProfilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeanceProfil::class);
    }

    public function add(SeanceProfil $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SeanceProfil $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return SeanceProfil[] Returns an array of SeanceProfil objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SeanceProfil
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findAllBySeance($value): array
   {
       return $this->createQueryBuilder('inscrit')
           ->Where('inscrit.seance = :val')
           ->setParameter('val', $value)
           ->orderBy('inscrit.profil', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

    public function findBy2ID($seanceID, $profilID): array
    {
        return $this->createQueryBuilder('seanceProfil')
        ->Where('seanceProfil.seance = :seanceID')
        ->setParameter('seanceID', $seanceID)
        ->andWhere('seanceProfil.profil = :profilID')
        ->setParameter('profilID', $profilID)
            
            ->getQuery()
            ->getResult();
    }

   
}
