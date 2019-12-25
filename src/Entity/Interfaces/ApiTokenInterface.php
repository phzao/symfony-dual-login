<?php

namespace App\Entity\Interfaces;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface ApiTokenInterface
 * @package App\Entity\Interfaces
 */
interface ApiTokenInterface
{
    /**
     * @return null|User
     */
    public function getUsuario(): ?User ;

    /**
     * @param null|User $usuario
     *
     * @return mixed
     */
    public function setUsuario(?User $usuario);

    /**
     * @return null|UserInterface
     */
    public function isValidToken(): ? UserInterface;

    /**
     * @return array
     */
    public function getDetailsToken(): array;
}