<?php

namespace App\Repository;

use App\Entity\ChartOfAccounts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChartOfAccounts>
 *
 * @method ChartOfAccounts|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChartOfAccounts|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChartOfAccounts[]    findAll()
 * @method ChartOfAccounts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChartOfAccountsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChartOfAccounts::class);
    }

    public function save(ChartOfAccounts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ChartOfAccounts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return ChartOfAccounts[] Returns an array of ChartOfAccounts objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ChartOfAccounts
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAllByName($value): ?array
    {
        return $this->createQueryBuilder('chartOfAccounts')
            ->andWhere('chartOfAccounts.name LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->orderBy('chartOfAccounts.code', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findAllByCode($value): ?array
    {
        return $this->createQueryBuilder('chartOfAccounts')
            ->andWhere('chartOfAccounts.code = :val')
            ->setParameter('val',  $value )
            -> orderBy('chartOfAccounts.code', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // public function findAllByMovable($value): ?array
    // {
    //     return $this->createQueryBuilder('chartOfAccounts')
    //         ->andWhere('chartOfAccounts.movable = :val')
    //         ->setParameter('val',  $value )
    //         -> orderBy('chartOfAccounts.code', 'DESC')
    //         ->getQuery()
    //         ->getResult();
    // }

    public function findAllInOrder(): ?array
    {
        return $this->createQueryBuilder('chartOfAccounts')
        ->orderBy('chartOfAccounts.code', 'DESC')
        ->getQuery()
            ->getResult();
    }


      public function findMaxChartOfAccount($value)
    {
        return $this->createQueryBuilder('account')
            ->select('account.code')
            ->where('account.code-?val > 0')
            ->andWhere('t.code-?val < 999')
            ->setParameter(val ,  $value)
            ->orderBy('t.code', "DESC")
            ->getQuery()
            ->setMaxResults(1)
            ->getResult();
    }

}
