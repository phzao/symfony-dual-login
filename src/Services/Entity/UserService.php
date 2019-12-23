<?php

namespace App\Services\Entity;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Services\Entity\Interfaces\UserServiceInterface;

/**
 * Class UserService
 * @package App\Services\Entity
 */
class UserService implements UserServiceInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->repository = $userRepository;
    }

    /**
     * @param array $data
     *
     * @return User|mixed
     */
    public function register(array $data)
    {
        $user = new User();
        $user->setAttributes($data);

        if (empty($data["password"])) {
            $user->setPassword(rand(1000, 9999));
        }

        $this->repository->save($user);

        return $user;
    }
}