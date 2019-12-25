<?php

namespace App\Services\Entity;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Services\Entity\Interfaces\UserServiceInterface;
use App\Utils\Enums\GeneralTypes;
use App\Utils\HandleErrors\ErrorMessage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

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

    /**
     * @param User   $user
     * @param string $status
     */
    public function updateStatus(User $user, string $status)
    {
        $status_list = GeneralTypes::STATUS_LIST;

        if (!in_array($status, $status_list)) {
            $list = ["status" => "This status is invalid!"];
            $msg = ErrorMessage::getMessageToJson($list);
            throw new UnprocessableEntityHttpException($msg);
        }
        $user->setEnable();

        if ($status==="disable") {
            $user->setDisable();
        }

        $this->repository->save($user);
    }

    /**
     * @param string $email
     *
     * @return null|User
     */
    public function getUserByEmail(string $email): ? User
    {
        return $this->repository->getByEmail($email);
    }

    /**
     * @param string $uuid
     *
     * @return null|User
     */
    public function getUserByIdIfExist(string $uuid): ? User
    {
        $user = $this->repository->getByID($uuid);

        if (!$user) {
            throw new NotFoundHttpException("There is no user with this id $uuid");
        }

        return $user;
    }
}