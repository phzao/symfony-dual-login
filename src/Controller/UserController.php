<?php

namespace App\Controller;

use App\Repository\Interfaces\UserRepositoryInterface;
use App\Services\Entity\Interfaces\UserServiceInterface;
use Doctrine\DBAL\Exception\ConnectionException;
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
     * @param UserServiceInterface $userService
     *
     * @return JsonResponse
     */
    public function show(string $uuid, UserServiceInterface $userService)
    {
        try {
            $user = $userService->getUserByIdIfExist($uuid);

            return $this->respond($user->getFullData());

        } catch (UnprocessableEntityHttpException $exception) {

            return $this->respondValidationError($exception->getMessage());
        }  catch (NotFoundHttpException $exception) {

            return $this->respondNotFound($exception->getMessage());
        } catch (\Exception $exception) {

            return $this
                        ->errorMessage($exception->getMessage())
                        ->setStatusCode(400);
        }
    }

    /**
     * @Route("/{uuid}", methods={"DELETE"})
     * @param string $uuid
     *
     * @return JsonResponse
     */
    public function delete(string $uuid)
    {
        try {
            $user = $this->repository->getByID($uuid);

            $user->delete();
            $user->setDisable();
            $this->repository->save($user);

            return $this->respondUpdatedResource();

        } catch (UnprocessableEntityHttpException $exception) {

            return $this->respondValidationError($exception->getMessage());
        }  catch (NotFoundHttpException $exception) {

            return $this->respondNotFound($exception->getMessage());
        } catch (\Exception $exception) {

            return $this
                        ->errorMessage($exception->getMessage())
                        ->setStatusCode(400);
        }
    }

    /**
     * @Route("/{uuid}/{status}",
     *     methods={"PUT"},
     *     requirements={
     *      "status": "enable|disable"
     *      })
     * @param string               $uuid
     * @param string               $status
     * @param UserServiceInterface $userService
     *
     * @return JsonResponse
     */
    public function updateStatus(string $uuid,
                                 string $status,
                                 UserServiceInterface $userService)
    {
        try {

            $user = $userService->getUserByIdIfExist($uuid);
            $userService->updateStatus($user, $status);

            return $this->respondUpdatedResource();

        } catch (UnprocessableEntityHttpException $exception) {

            return $this->respondValidationError($exception->getMessage());
        }  catch (NotFoundHttpException $exception) {

            return $this->respondNotFound($exception->getMessage());
        } catch (\Exception $exception) {

            return $this
                        ->errorMessage($exception->getMessage())
                        ->setStatusCode(400);
        }
    }

    /**
     * @Route("", methods={"GET"}, name="default")
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

            return $this
                        ->errorMessage($exception->getMessage())
                        ->setStatusCode(400);
        }
    }
}