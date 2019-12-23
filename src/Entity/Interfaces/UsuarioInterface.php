<?php

namespace App\Entity\Interfaces;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface UsuarioInterface
 * @package App\Entity\Interfaces
 */
interface UsuarioInterface extends UserInterface
{
    /**
     * @return array
     */
    public function getLoginData(): array;
}