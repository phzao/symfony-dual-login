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
    public function getUser(): ?User ;

    /**
     * @param null|User $user
     *
     * @return mixed
     */
    public function setUser(?User $user);

    /**
     * @return null|UserInterface
     */
    public function isValidToken(): ? UserInterface;

    /**
     * @return array
     */
    public function getDetailsToken(): array;
}