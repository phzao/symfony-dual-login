<?php

namespace App\Repository\Interfaces;

use App\Entity\ApiToken;

/**
 * Interface UserRepositoryInterface
 * @package App\Repository\Interfaces
 */
interface ApiTokenRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string $user_id
     *
     * @return mixed|ApiToken
     */
    public function getTheLastTokenByUser(string $user_id): ?ApiToken;
}