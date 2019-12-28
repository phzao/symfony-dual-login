<?php

namespace App\Tests\Services\Entity;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Tests\Services\Login\LoadLoginService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Class LoginServiceTest
 * @package App\Tests\Services\Entity
 */
class LoginServiceTest extends WebTestCase
{
    use LoadLoginService, LoadUserService;

    public function testLoginDataWithoutEmailFail()
    {
        $loginService = $this->getLoginService();
        $this->expectException(UnprocessableEntityHttpException::class);
        $loginService->isLoginDataValid(["password" => "123456"]);
    }

    public function testLoginDataWithoutPasswordFail()
    {
        $loginService = $this->getLoginService();
        $this->expectException(UnprocessableEntityHttpException::class);
        $loginService->isLoginDataValid(["email" => "me@me.com"]);
    }

    public function testLoginDataWithoutDataFail()
    {
        $loginService = $this->getLoginService();
        $this->expectException(UnprocessableEntityHttpException::class);
        $loginService->isLoginDataValid([]);
    }

    public function testCredentialsUserNotRegisteredFail()
    {
        $user = new User();
        $loginService = $this->getLoginService();

        $this->expectException(BadCredentialsException::class);
        $loginService->isValidCredentials($user, '121212');
    }

    public function testCredentialsPasswordInvalidFail()
    {
        $user = new User();
        $encoder = $this->getUserPasswordEncoder();
        $user->setPassword('101010');
        $user->encryptPassword($encoder);
        $loginService = $this->getLoginService();

        $this->expectException(BadCredentialsException::class);
        $loginService->isValidCredentials($user, '121212');
    }

    public function testCredentialsValidSuccess()
    {
        $user = new User();
        $encoder = $this->getUserPasswordEncoder();
        $user->setPassword('101010');
        $user->encryptPassword($encoder);
        $loginService = $this->getLoginService();

        $data = $loginService->isValidCredentials($user, '101010');
        $this->assertNull($data);
    }

    public function testLoginFail()
    {
        $loginService = $this->getLoginService();
        $user = new User();
        $this->expectException(EntityNotFoundException::class);
        $loginService->getLogin($user);
    }

//    public function testLoginSuccess()
//    {
//        $loginService = $this->getLoginService();
//        $userService  = $this->getUserService();
//
//        $data     = ["email" => "me@me.com", "password" => "121212"];
//        $user     = $userService->register($data);
//        $apiToken = $loginService->getLogin($user);
//
//        $this->assertInstanceOf(ApiToken::class, $apiToken);
//        $this->assertCount(4, $apiToken->getDetailsToken());
//        $this->assertIsString($apiToken->getId());
//    }
}