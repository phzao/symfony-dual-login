<?php

namespace App\Services\Entity;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Services\Entity\Interfaces\UserServiceInterface;
use App\Utils\Enums\GeneralTypes;
use App\Utils\HandleErrors\ErrorMessage;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @package App\Services\Entity
 */
final class UserService implements UserServiceInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->repository = $userRepository;
    }

    /**
     * @throws \Exception
     */
    public function register(array $data): User
    {
        $user = new User();
        $user->setAttributes($data);

        if (empty($data["password"])) {
            $user->setPassword(rand(1000, 9999));
        }

        $this->repository->save($user);

        return $user;
    }

    public function updateStatus($user, string $status)
    {
        $user->setAttribute('status', $status);

        $this->repository->save($user);
    }

    public function getUserByEmail(string $email): ? User
    {
        return $this->repository->getOneUserByEmail($email);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function getUserByIdOrFail(string $uuid): ? User
    {
        $user = $this->repository->getOneByID($uuid);

        if (!$user) {
            throw new EntityNotFoundException("There is no user with this id $uuid");
        }

        return $user;
    }

    public function getUserByEmailToLoginOrFail(string $email): ?User
    {
        $user = $this->repository->getOneUserByEmail($email);

        if (!$user) {
            throw new NotFoundHttpException('The email is wrong!');
        }

        return $user;
    }
}