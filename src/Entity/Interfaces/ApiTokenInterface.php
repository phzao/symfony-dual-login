<?php

namespace App\Entity\Interfaces;

use App\Entity\User;

/**
 * Interface ApiTokenInterface
 * @package App\Entity\Interfaces
 */
interface ApiTokenInterface
{
//    /**
//     * @return null|string
//     */
//    public function getId(): ?string;

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

    public function makeExpiredToken();

    /**
     * @return array
     */
    public function getDetailsToken(): array;
}