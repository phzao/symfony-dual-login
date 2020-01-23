<?php

namespace App\Tests\Entity;

use App\Entity\Interfaces\ModelInterface;
use App\Entity\Interfaces\SimpleTimeInterface;
use App\Entity\Interfaces\UsuarioInterface;
use App\Entity\User;
use App\Utils\Enums\GeneralTypes;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserTest
 * @package App\Tests\Entity
 */
class UserTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testInitiateUser()
    {
        $user = new User();

        $this->assertInstanceOf(UsuarioInterface::class, $user);
        $this->assertInstanceOf(UserInterface::class, $user);
        $this->assertInstanceOf(ModelInterface::class, $user);
        $this->assertInstanceOf(SimpleTimeInterface::class, $user);


        $this->assertIsArray($user->getRoles());
    }
}