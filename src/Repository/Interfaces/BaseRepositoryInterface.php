<?php

namespace App\Repository\Interfaces;

/**
 * @package App\Repository\Interfaces
 */
interface BaseRepositoryInterface
{
    public function save($entity);

    public function getAllBy(array $data): ?array;

    public function getOneBy(array $array);

    public function remove($entity);
}