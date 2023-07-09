<?php

namespace App\Repository;

use App\Entity\ResultFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResultFile>
 *
 * @method ResultFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResultFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResultFile[]    findAll()
 * @method ResultFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResultFile::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ResultFile $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ResultFile $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
