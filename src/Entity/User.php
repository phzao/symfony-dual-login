<?php

namespace App\Entity;

use App\Entity\Interfaces\ModelInterface;
use App\Entity\Interfaces\SimpleTimeInterface;
use App\Entity\Interfaces\UsuarioInterface;
use App\Entity\Traits\SimpleTime;
use App\Utils\Enums\GeneralTypes;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends ModelBase implements UsuarioInterface, ModelInterface, SimpleTimeInterface
{
    use SimpleTime;

    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id()
     * @ORM\Column(name="id", type="uuid", unique=true)
     */
    protected $id;

    /**
     * @Assert\NotBlank(message="Email is required!")
     * @Assert\Email
     * @Assert\Length(
     *      min = 6,
     *      max = 180,
     *      minMessage = "Type must be at least {{ limit }} characters long",
     *      maxMessage = "Type cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=180, unique=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @var string The hashed password
     * @Assert\NotBlank(message="A password is required!")
     * @Assert\Length(
     *      min = 6,
     *      max = 6,
     *      minMessage = "Password must be at least {{ limit }} characters long",
     *      maxMessage = "Password cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @var string The status user
     * @Assert\NotBlank(message="A status is required!")
     * @Assert\Choice({"enable", "disable", "blocked"})
     * @ORM\Column(type="string", length=20)
     */
    protected $status = GeneralTypes::STATUS_ENABLE;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deleted_at;

    /**
     * @var array
     */
    protected $attributes = [
        "email",
        "password"
    ];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ApiToken", mappedBy="usuario", orphanRemoval=true)
     */
    private $apiTokens;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->apiTokens  = new ArrayCollection();
        $this->id         = Uuid::uuid4()->toString();
        $this->created_at = new \DateTime("now");
    }

    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function encryptPassword(UserPasswordEncoderInterface $passwordEncoder)
    {
        $password       = empty($this->password) ? "" : $this->password;
        $this->password = $passwordEncoder->encodePassword($this, $password);
    }

    /**
     * @return array
     */
    public function getFullData(): array
    {
        return [
            "id"                 => $this->id,
            "email"              => $this->email,
            "created_at"         => $this->getDateTimeStringFrom('created_at'),
            "status"             => $this->status,
            "status_description" => GeneralTypes::getStatusDescription($this->status)
        ];
    }

    /**
     * @return Collection|ApiToken[]
     */
    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    /**
     * @param ApiToken $apiToken
     *
     * @return User
     */
    public function addApiToken(ApiToken $apiToken): self
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens[] = $apiToken;
            $apiToken->setUsuario($this);
        }

        return $this;
    }

    /**
     * @param ApiToken $apiToken
     *
     * @return User
     */
    public function removeApiToken(ApiToken $apiToken): self
    {
        if ($this->apiTokens->contains($apiToken)) {
            $this->apiTokens->removeElement($apiToken);
            // set the owning side to null (unless already changed)
            if ($apiToken->getUsuario() === $this) {
                $apiToken->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getLoginData(): array
    {
        return [
            "id"    => $this->id,
            "email" => $this->email
        ];
    }

    /**
     * @return User
     */
    public function setDisable(): self
    {
        $this->status = GeneralTypes::STATUS_DISABLE;
        return $this;
    }

    /**
     * @return User
     */
    public function setEnable(): self
    {
        $this->status = GeneralTypes::STATUS_ENABLE;
        return $this;
    }

    public function delete(): void
    {
        $this->deleted_at = new \DateTime('now');
    }
}
