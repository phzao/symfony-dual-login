<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

/**
 * Class BaseRepository
 * @package App\Repository
 */
class BaseRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param $entity
     */
    public function save($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function update()
    {
        $this->entityManager->flush();
    }

    /**
     * @param array $data
     * @return array
     */
    public function getArrayAll(array $data): array
    {
        $query = $this
                    ->objectRepository
                    ->createQueryBuilder('c')
                    ->getQuery();

        return $query
                    ->setHint(Query::HINT_INCLUDE_META_COLUMNS, true)
                    ->getArrayResult();
    }

}