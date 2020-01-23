<?php

namespace App\Controller;

use App\Repository\Interfaces\UserRepositoryInterface;
use App\Services\Entity\Interfaces\UserServiceInterface;
use App\Services\Log\Interfaces\LoggerServiceInterface;
use App\Utils\Enums\GeneralTypes;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller
 * @Route("/api/v1/users")
 */
class UserController extends APIController
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    public function __construct(UserRepositoryInterface $userRepository,
                                LoggerServiceInterface $loginService)
    {
        parent::__construct($loginService);
        $this->repository = $userRepository;
    }

    /**
     * @Route("/me", methods={"GET"})
     */
    public function show()
    {
        try {
            $user = $this->getUser();

            return $this->respondSuccess($user->getFullData());

        } catch (\Exception $exception) {

            return $this->respondBadRequestError($exception->getMessage());
        }
    }

    /**
     * @Route("/my-status-to/{status}", methods={"PUT"})
     */
    public function updateStatus(string $status,
                                 UserServiceInterface $userService)
    {
        try {

            GeneralTypes::isValidDefaultStatusOrFail($status);

            $userService->updateStatus($this->getUser(), $status);

            return $this->respondUpdatedResource();

        } catch (UnprocessableEntityHttpException $exception) {

            return $this->respondValidationFail($exception->getMessage());
        }  catch (NotFoundHttpException $exception) {

            return $this->respondNotFoundError($exception->getMessage());
        } catch (\Exception $exception) {

            return $this->respondBadRequestError($exception->getMessage());
        }
    }
}