<?php

namespace App\Services\Entity\Interfaces;

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
}