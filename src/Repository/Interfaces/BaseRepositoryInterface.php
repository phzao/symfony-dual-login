<?php

namespace App\Repository\Interfaces;

/**
 * Interface BaseRepositoryInterface
 * @package App\Repository\Interfaces
 */
interface BaseRepositoryInterface
{
    /**
     * @param $entity
     * @return mixed
     */
    public function save($entity);
}