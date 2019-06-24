<?php

namespace App\Repository;

use App\Entity\ItemCounter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ItemCounter|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemCounter|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemCounter[]    findAll()
 * @method ItemCounter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemCounterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ItemCounter::class);
    }

    // /**
    //  * @return ItemCounter[] Returns an array of ItemCounter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ItemCounter
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function transform(ItemCounter $item)
    {
        return [
                'id'    => (int) $item->getId(),
                'title' => (string) $item->getTitle(),
                'count' => (int) $item->getCount()
        ];
    }

    public function transformAll()
    {
        $items = $this->findAll();
        $itemsArray = [];

        foreach ($items as $item) {
            $itemsArray[] = $this->transform($item);
        }

        return $itemsArray;
    }
}
