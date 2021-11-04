<?php

namespace App\Repository;

use App\Entity\Hash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Hash|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hash|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hash[]    findAll()
 * @method Hash[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HashRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hash::class);
    }

    // /**
    //  * @return Hash[] Returns an array of Hash objects
    //  */
    
    public function findHashTentativas($tentativas)
    {
        $fields = array('h.id', 'h.Batch', 'h.string', 'h.chave');
        return $this->createQueryBuilder('h')
            ->select($fields)
            ->where('h.tentativas < :fields')
            ->setParameter('fields', $tentativas)
            ->orderBy('h.Batch', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
