<?php

namespace App\Entity;

use App\Entity\Interfaces\ApiTokenInterface;
use App\Entity\Traits\SimpleTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="api_tokens")
 */
class ApiToken implements ApiTokenInterface
{
    use SimpleTime;

    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id()
     * @ORM\Column(name="id", type="uuid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expire_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expired_at;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="apiTokens")
     * @ORM\JoinColumn(referencedColumnName="id" ,nullable=false)
     */
    private $usuario;

    /**
     * ApiToken constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->token     = bin2hex(random_bytes(60));
        $this->expire_at = new \DateTime('+24 hour');
        $this->id        = Uuid::uuid4();
    }

//    /**
//     * @return null|string
//     */
//    public function getId(): ?string
//    {
//        return $this->id;
//    }
//
//    /**
//     * @return null|string
//     */
//    public function getToken(): ?string
//    {
//        return $this->token;
//    }
//
//    /**
//     * @param string $token
//     *
//     * @return ApiToken
//     */
//    public function setToken(string $token): self
//    {
//        $this->token = $token;
//
//        return $this;
//    }
//
    /**
     * @return null|\DateTimeInterface
     */
    public function getExpireAt(): ?\DateTimeInterface
    {
        return $this->expire_at;
    }
//
//    public function setExpireAt(\DateTimeInterface $expire_at): self
//    {
//        $this->expire_at = $expire_at;
//
//        return $this;
//    }
//
//    public function getExpiredAt(): ?\DateTimeInterface
//    {
//        return $this->expired_at;
//    }
//
//    public function setExpiredAt(?\DateTimeInterface $expired_at): self
//    {
//        $this->expired_at = $expired_at;
//
//        return $this;
//    }
//
//    /**
//     * @return null|\DateTimeInterface
//     */
//    public function getExpiresAt(): ?\DateTimeInterface
//    {
//        return $this->expire_at;
//    }

    /**
     * @return null|User
     */
    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    /**
     * @param null|User $usuario
     *
     * @return ApiToken
     */
    public function setUsuario(?User $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * @return array
     */
    public function getDetailsToken(): array
    {
        return [
            "id_token"  => $this->id,
            "token"     => $this->token,
            "usuario"   => $this->usuario->getLoginData(),
            "expire_at" => $this->getDateTimeStringFrom("expire_at")
        ];
    }

    /**
     * @throws \Exception
     */
    public function makeExpiredToken()
    {
        $this->expired_at = new \DateTime("now");
    }


}
