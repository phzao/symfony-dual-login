<?php

namespace App\Entity;

use App\Entity\Interfaces\ApiTokenInterface;
use App\Entity\Traits\SimpleTime;
use App\Entity\Traits\UuidControl;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="api_tokens")
 */
class ApiToken implements ApiTokenInterface
{
    use SimpleTime, UuidControl;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $token;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $expire_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $expired_at;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="apiTokens")
     * @ORM\JoinColumn(referencedColumnName="id" ,nullable=false)
     */
    protected $user;

    /**
     * ApiToken constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->token     = bin2hex(random_bytes(60));
        $this->expire_at = new \DateTime('+24 hour');
    }

    /**
     * @return null|\DateTimeInterface
     */
    public function getExpireAt(): ?\DateTimeInterface
    {
        return $this->expire_at;
    }

    /**
     * @return null|User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param null|User $user
     *
     * @return ApiToken
     */
    public function setUser(?User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return array
     */
    public function getDetailsToken(): array
    {
        $login = empty($this->user) ? null : $this->user->getLoginData();

        return [
            "id"        => $this->id,
            "token"     => $this->token,
            "user"      => $login,
            "expire_at" => $this->getDateTimeStringFrom("expire_at")
        ];
    }

    /**
     * @return mixed|UserInterface
     * @throws \Exception
     */
    public function isValidToken(): ? UserInterface
    {
        if (!empty($this->expired_at)) {
            return false;
        }

        $now = new \DateTime("now");

        if ($now < $this->expire_at) {
            return $this->user;
        }

        $this->expired_at = new \DateTime("now");

        return false;
    }
}
