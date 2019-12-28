<?php

namespace App\Entity\Interfaces;

use App\Entity\User;
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

    /**
     * @return User
     */
    public function setDisable(): User;

    /**
     * @return User
     */
    public function setEnable(): User;

    public function delete(): void;

    /**
     * @return string
     */
    public function getStatus(): string;

    public function updatedTime(): void;
}