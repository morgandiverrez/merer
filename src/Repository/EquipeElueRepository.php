<?php

namespace App\Repository;

use App\Entity\EquipeElue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EquipeElue>
 *
 * @method EquipeElue|null find($id, $lockMode = null, $lockVersion = null)
 * @method EquipeElue|null findOneBy(array $criteria, array $orderBy = null)
 * @method EquipeElue[]    findAll()
 * @method EquipeElue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipeElueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EquipeElue::class);
    }

    public function add(EquipeElue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EquipeElue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return EquipeElue[] Returns an array of EquipeElue objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?EquipeElue
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findByID($value): ?array
    {
        return $this->createQueryBuilder('EquipeElue')
        ->andWhere('EquipeElue.id = :val')
            ->setParameter('val', $value)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }
}
