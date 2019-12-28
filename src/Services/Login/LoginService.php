<?php

namespace App\Services\Login;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Repository\Interfaces\ApiTokenRepositoryInterface;
use App\Utils\HandleErrors\ErrorMessage;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class LoginService
 * @package App\Services\Entity
 */
final class LoginService
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var ApiTokenRepositoryInterface
     */
    private $tokenRepository;

    /**
     * LoginService constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ApiTokenRepositoryInterface  $tokenRepository
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,
                                ApiTokenRepositoryInterface $tokenRepository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * @param array $data
     */
    public function isLoginDataValid(array $data)
    {
        $error = [];

        if (empty($data["email"])) {
            $error["email"] = "A email is required to login!";
        }

        if (empty($data["password"])) {
            $error["password"] = "A password is required to login!";
        }

        if (empty($error)) {
            return ;
        }

        $msg = ErrorMessage::getMessageToJson($error);

        throw new UnprocessableEntityHttpException($msg);
    }

    /**
     * @param        $user
     * @param string $password
     */
    public function isValidCredentials($user, string  $password)
    {
        if ($user instanceof UserInterface &&
            $this->passwordEncoder->isPasswordValid($user, $password)) {
            return ;
        }
        $msg = ErrorMessage::getErrorMessage("A email or password is incorrect!", "fail");
        throw new BadCredentialsException($msg);
    }

    /**
     * @param User $user
     *
     * @return null|ApiToken
     * @throws \Exception
     */
    public function getLogin(User $user): ?ApiToken
    {
        if (!$user->getId()) {
            $msg = ErrorMessage::getErrorMessage("A valid user is required!", "fail");
            throw new EntityNotFoundException($msg);
        }

        $token = $this->tokenRepository->getTheLastTokenByUser($user->getId());

        if ($token) {
            return $token;
        }

        $apiToken = new ApiToken();
        $apiToken->setUser($user);
        $apiToken->generateUuid();

        $this->tokenRepository->save($apiToken);

        return $apiToken;
    }
}