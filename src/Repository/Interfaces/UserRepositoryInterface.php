<?php

namespace App\Repository\Interfaces;

use App\Entity\User;

/**
 * Interface UserRepositoryInterface
 * @package App\Repository\Interfaces
 */
interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return null|User
     */
    public function getByID(string $id): ?User;

    /**
     * @param string $email
     *
     * @return User|mixed
     */
    public function getByEmail(string $email): ?User;

    /**
     * @param array $parameters
     *
     * @return array
     */
    public function getList(array $parameters = []): array;
}