<?php

namespace App\Shopping\Infrastructure\Repository\Payment;

use App\Shopping\Domain\Model\Payment\Payment;
use App\Shopping\Domain\Model\Payment\PaymentId;
use Doctrine\ORM\EntityManagerInterface;
use App\Shopping\Domain\Model\Payment\PaymentRepository;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: 'repository.payment', public: true)]
#[When(env: 'dev')]
#[When(env: 'prod')]
class DoctrinePaymentRepository implements PaymentRepository
{
    public function __construct(private EntityManagerInterface $manager)
    {
        
    }

    public function add(Payment $payment): void
    {
        $this->getEntityManager()->persist($payment);

        $this->getEntityManager()->flush();
    }

    public function remove(Payment $payment): void
    {
        $this->getEntityManager()->remove($payment);
        $this->getEntityManager()->flush();
    }

    public function find(PaymentId $paymentId): ?Payment
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $payment = $qb->select('c')
            ->from(Payment::class, 'c')
            ->andWhere(
                $qb->expr()->eq('c.id.id', ':id')
            )
            ->setParameter('id', $paymentId->toBinary())
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $payment;
    }

    private function getEntityManager()
    {
        return $this->manager;
    }
}