<?php

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Menu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Menu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Menu[]    findAll()
 * @method Menu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    /**
     * @return Menu[] Returns an array of Equipment objects
     */

    public function findByCompany($companyId)
    {
        return $this->createQueryBuilder('menu')
            ->andWhere('menu.company = :val')
            ->setParameter('val', $companyId)
            ->orderBy('menu.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

     /**
     * @param $companyId
     * @param $id
     * @return Menu
     * @throws NonUniqueResultException
     */
    public function findOneByCompanyID($companyId,$id)
    {
        return $this->createQueryBuilder('menu')
            ->andWhere('menu.company = :company')
            ->andWhere('menu.id = :id')
            ->setParameter('company', $companyId)
            ->setParameter('id', $id)
            ->orderBy('menu.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @return int 
     */

    public function SumByCompanyVisits($companyId)
    {
        return intval($this->createQueryBuilder('menu')
            ->andWhere('menu.company = :val')
            ->setParameter('val', $companyId)
            ->select('SUM(menu.visits) as total_visits')
            ->getQuery()
            ->getSingleScalarResult())
            ;
    }

     /**
     * @return int 
     */

    public function SumByCompanyCalls($companyId)
    {
        return intval($this->createQueryBuilder('menu')
            ->andWhere('menu.company = :val')
            ->setParameter('val', $companyId)
            ->select('SUM(menu.phoneVisits) as total_calls')
            ->getQuery()
            ->getSingleScalarResult())
            ;
    }

     /**
     * @return int 
     */

    public function SumByCompanyWhatsapp($companyId)
    {
        return intval($this->createQueryBuilder('menu')
            ->andWhere('menu.company = :val')
            ->setParameter('val', $companyId)
            ->select('SUM(menu.whatsappVisits) as total_whatsapp')
            ->getQuery()
            ->getSingleScalarResult())
            ;
    }

    // /**
    //  * @return Menu[] Returns an array of Menu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Menu
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
