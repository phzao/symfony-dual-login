<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Services\Login\LoginService;
use App\Services\Validation\ValidationService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Class RegisterController
 * @package App\Controller
 */
class RegisterController extends APIController
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
     * @Route("/register", methods={"POST"})
     * @param Request                      $request
     * @param ValidationService            $validationService
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return JsonResponse
     */
    public function save(Request $request,
                         ValidationService $validationService,
                         UserPasswordEncoderInterface $passwordEncoder)
    {
        try {

            $data = $request->request->all();
            $user = new User();

            $user->setAttributes($data);

            $validationService->validating($user);

            $user->encryptPassword($passwordEncoder);
            $user->generateUuid();
            $this->repository->save($user);

            return $this->respondCreated($user->getFullData());

        } catch(UniqueConstraintViolationException $PDOException){
            $msg = $PDOException->getMessage();

            return $this->errorMessage($msg);
        } catch (UnprocessableEntityHttpException $exception) {

            return $this->respondValidationError($exception->getMessage());
        }  catch (NotFoundHttpException $exception) {

            return $this->respondNotFound($exception->getMessage());
        } catch (\Exception $exception) {

            return $this->errorMessage($exception->getMessage());
        }
    }

    /**
     * @Route("/authenticate", methods={"POST"})
     *
     * @param Request                     $request
     * @param LoginService                $loginService
     *
     * @return JsonResponse
     */
    public function login(Request $request,
                          LoginService $loginService)
    {
        try {

            $data = $request->request->all();

            $loginService->isLoginDataValid($data);

            $user = $this->repository->getByEmail($data["email"]);

            $loginService->isValidCredentials($user, $data["password"]);

            $loginData = $loginService->getLogin($user);

            return $this->respondCreated($loginData->getDetailsToken());

        } catch(UniqueConstraintViolationException $PDOException){
            $msg = $PDOException->getMessage();

            return $this->errorMessage($msg);
        }  catch (UnprocessableEntityHttpException $exception) {

            return $this->respondValidationError($exception->getMessage());
        }  catch (NotFoundHttpException $exception) {

            return $this->respondNotFound($exception->getMessage());
        } catch (BadCredentialsException $exception) {

            return $this->respondWithInvalidCredentials($exception->getMessage());
        } catch (\Exception $exception) {

            return $this->errorMessage($exception->getMessage());
        }
    }
}