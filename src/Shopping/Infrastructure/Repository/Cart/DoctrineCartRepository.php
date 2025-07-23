<?php

namespace App\Shopping\Infrastructure\Repository\Cart;

use App\Shopping\Domain\Model\Cart\Cart;
use App\Shopping\Domain\Model\Cart\CartId;
use Doctrine\ORM\EntityManagerInterface;
use App\Shopping\Domain\Model\Cart\Item;
use App\Shopping\Domain\Model\Cart\CartRepository;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: 'repository.cart', public: true)]
#[When(env: 'dev')]
#[When(env: 'prod')]
class DoctrineCartRepository implements CartRepository
{
    public function __construct(private EntityManagerInterface $manager)
    {
        
    }

    public function add(Cart $cart): void
    {
        $this->getEntityManager()->persist($cart);

        $this->addItems($cart);

        $this->getEntityManager()->flush();
    }

    private function addItems(Cart $cart)
    {
        $items = $cart->items();

        if (count($items) == 0) {
            return ;
        }

        $itemRepository = $this->getEntityManager()->getRepository(Item::class);
        $storedItems = $itemRepository->findBy([
            'cartId.id' => $cart->id()->toBinary()
        ]);

        if (count($storedItems) > 0) {
            foreach ($storedItems as $storedItem) {
                if (!$items->contains($storedItem)) {
                    $this->getEntityManager()->remove($storedItem);
                }
            }
        }

        foreach ($items->toArray() as $item) {
            $this->getEntityManager()->persist($item);
        }
    }

    public function remove(Cart $cart): void
    {
        $this->getEntityManager()->remove($cart);
        $this->getEntityManager()->flush();
    }

    public function find(CartId $cartId): ?Cart
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $cart = $qb->select('c')
            ->from(Cart::class, 'c')
            ->andWhere(
                $qb->expr()->eq('c.id.id', ':id')
            )
            ->setParameter('id', $cartId->toBinary())
            ->getQuery()
            ->getOneOrNullResult()
        ;

        $this->loadItems($cart);

        return $cart;
    }

    private function loadItems(?Cart $cart)
    {
        if (null === $cart) {
            return ;
        }

        $storedItems = $this->getEntityManager()
            ->getRepository(Item::class)
            ->findBy([
                'cartId.id' => $cart->id()->toBinary()
            ])
        ;

        if (count($storedItems) == 0) {
            return ;
        }
        
        foreach ($storedItems as $item) {
            $cart->items()->add($item);
        }
    }

    private function getEntityManager()
    {
        return $this->manager;
    }
}