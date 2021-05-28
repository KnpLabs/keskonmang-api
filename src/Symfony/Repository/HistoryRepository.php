<?php

namespace App\Symfony\Repository;

use App\Domain\History;
use App\Domain\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class HistoryRepository extends ServiceEntityRepository
{
    private const LIMIT = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }

    public function findAllForUser(User $user, int $page = 1): array
    {
        return $this
            ->createQueryBuilder('history')
            ->andWhere('history.user = :user')
            ->setParameter('user', $user)
            ->orderBy('history.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * self::LIMIT)
            ->setMaxResults(self::LIMIT)
            ->getQuery()
            ->getResult()
        ;
    }

    public function save(History $history): void
    {
        $this->_em->persist($history);
        $this->_em->flush();
    }
}
