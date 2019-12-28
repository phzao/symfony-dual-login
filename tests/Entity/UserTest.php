<?php

namespace App\Tests\Entity;

use App\Entity\Interfaces\ModelInterface;
use App\Entity\Interfaces\SimpleTimeInterface;
use App\Entity\Interfaces\UsuarioInterface;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserTest
 * @package App\Tests\Entity
 */
class UserTest extends TestCase
{
    public function testUserEmpty()
    {
        $user = new User();

        $this->assertNull($user->getId());
        $this->assertIsString($user->getUsername());

        $this->assertInstanceOf(UsuarioInterface::class, $user);
        $this->assertInstanceOf(UserInterface::class, $user);
        $this->assertInstanceOf(ModelInterface::class, $user);
        $this->assertInstanceOf(SimpleTimeInterface::class, $user);

        $loginData = $user->getLoginData();

        $this->assertArrayHasKey('id', $loginData);
        $this->assertArrayHasKey('email', $loginData);
        $this->assertNull($loginData["id"]);
        $this->assertNull($loginData["email"]);
        $this->assertIsArray($loginData);
        $this->assertCount(2, $loginData);

        $userData = $user->getFullData();
        $now      = new \DateTime('now');

        $this->assertEquals($now->format('Y-m-d H:i:s'), $userData["created_at"]);
        $this->assertIsArray($userData);

        $keys = array_keys($userData);

        $this->assertEquals(["id", "email", "created_at", "updated_at", "status", "status_description"], $keys);
        $this->assertEmpty($user->getApiTokens());
        $this->assertCount(6, $userData);
        $this->assertNull($userData["email"]);
        $this->assertIsObject($user->getApiTokens());

        $user->setAttribute("email", "eu@eu.com");
        $this->assertEquals("eu@eu.com", $user->getUsername());

        $this->assertEmpty($user->getPassword());

        $attributes = [
            "email"    => "me@me.com",
            "password" => "12345",
        ];

        $user->setAttributes($attributes);
        $this->assertEquals("12345", $user->getPassword());
        $this->assertEquals("me@me.com", $user->getUsername());

        $user->generateUuid();
        $this->assertIsString($user->getId());
    }
}