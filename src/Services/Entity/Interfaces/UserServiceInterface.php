<?php

namespace App\Services\Entity\Interfaces;

use App\Entity\User;

/**
 * Interface UserServiceInterface
 * @package App\Services\Entity\Interfaces
 */
interface UserServiceInterface
{
    /**
     * @param array $data
     *
     * @return mixed
     */
    public function register(array $data);

    /**
     * @param string $email
     *
     * @return null|User
     */
    public function getUserByEmail(string $email): ? User;

    /**
     * @param User   $user
     * @param string $status
     *
     * @return mixed
     */
    public function updateStatus(User $user, string $status);

    /**
     * @param string $uuid
     *
     * @return null|User
     */
    public function getUserByIdIfExist(string $uuid): ? User;
}