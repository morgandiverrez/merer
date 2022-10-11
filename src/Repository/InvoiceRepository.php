<?php

namespace App\Repository;

use App\Entity\Invoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invoice>
 *
 * @method Invoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoice[]    findAll()
 * @method Invoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    public function add(Invoice $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Invoice $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Invoice[] Returns an array of Invoice objects
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

    //    public function findOneBySomeField($value): ?Invoice
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    public function findByID($value): ?Invoice
    {
        return $this->createQueryBuilder('invoice')
        ->andWhere('invoice.id = :val')
        ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findCurrentInvoiceImpressionOfAssociation($sigle): array
    {
        return $this->createQueryBuilder('invoice')
            ->innerJoin('invoice.association', 'association')
            ->Where('association.sigle LIKE :sigle')
            ->andWhere('invoice.code LIKE :code')
            ->andWhere('invoice.confirm LIKE TRUE')
            ->setParameter('sigle', $sigle )
            ->setParameter('code', 'FAEI%' )
            ->orderBy('invoice.creationDate', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findLastFAEI()
    {
        return $this->createQueryBuilder('invoice')
            ->select('t.code')
            ->andWhere('invoice.code LIKE :code')
            ->setParameter('code', 'FAEI%')
            ->orderBy('invoice.code', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()[0]['code'];
    }
}
