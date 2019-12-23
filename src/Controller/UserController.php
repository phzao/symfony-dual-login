<?php

namespace App\Controller;

use App\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/api/v1/users")
 */
class UserController extends APIController
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * UserController constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->repository = $userRepository;
    }

    /**
     * @Route("/{uuid}", methods={"GET"})
     * @param string $uuid
     *
     * @return JsonResponse
     */
    public function show(string $uuid)
    {
        try {
            $user = $this->repository->getByID($uuid);

            return $this->respond($user->getFullData());

        } catch (UnprocessableEntityHttpException $exception) {

            return $this->respondValidationError($exception->getMessage());
        }  catch (NotFoundHttpException $exception) {

            return $this->respondNotFound($exception->getMessage());
        } catch (\Exception $exception) {

            return $this->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @Route("/", methods={"GET"}, name="default")
     *
     * @return JsonResponse
     */
    public function list()
    {
        try {

            $listUsers = $this->repository->getList();

            return $this->respond($listUsers);

        } catch (UnprocessableEntityHttpException $exception) {

            return $this->respondValidationError($exception->getMessage());
        }  catch (NotFoundHttpException $exception) {

            return $this->respondNotFound($exception->getMessage());
        } catch (\Exception $exception) {

            return $this->respondWithErrors($exception->getMessage());
        }
    }
}