<?php

namespace App\Shopping\Infrastructure\Repository\Order;

use App\Shopping\Domain\Model\Order\Order;
use App\Shopping\Domain\Model\Order\OrderId;
use Doctrine\ORM\EntityManagerInterface;
use App\Shopping\Domain\Model\Order\OrderRepository;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: 'repository.order', public: true)]
#[When(env: 'dev')]
#[When(env: 'prod')]
class DoctrineOrderRepository implements OrderRepository
{
    public function __construct(private EntityManagerInterface $manager)
    {
        
    }

    public function add(Order $order): void
    {
        $this->getEntityManager()->persist($order);

        $this->getEntityManager()->flush();
    }

    public function remove(Order $order): void
    {
        $this->getEntityManager()->remove($order);
        $this->getEntityManager()->flush();
    }

    public function find(OrderId $orderId): ?Order
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $order = $qb->select('c')
            ->from(Order::class, 'c')
            ->andWhere(
                $qb->expr()->eq('c.id.id', ':id')
            )
            ->setParameter('id', $orderId->toBinary())
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $order;
    }

    private function getEntityManager()
    {
        return $this->manager;
    }
}