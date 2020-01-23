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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="users",
 *     indexes={
 *     @ORM\Index(name="users_email_status_type_idx", columns={"email", "status"}),
 * })
 */
class User extends ModelBase implements UsuarioInterface, ModelInterface, SimpleTimeInterface
{
    use SimpleTime;

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", columnDefinition="DEFAULT uuid_generate_v4()")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @Assert\NotBlank(message="Email is required!")
     * @Assert\Email
     * @Assert\Length(
     *      min = 6,
     *      max = 180,
     *      minMessage = "Email must be at least {{ limit }} characters long",
     *      maxMessage = "Email cannot be longer than {{ limit }} characters"
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
     * @Assert\NotBlank(message="The password is required!")
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
     * @Assert\NotBlank(message="The status is required!")
     * @Assert\Choice({"enable", "disable", "blocked"})
     * @ORM\Column(type="string", length=20, options={"default": "enable"})
     */
    protected $status;

    /**
     * @Assert\Length(
     *      max = 70,
     *      maxMessage = "Name cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    protected $attributes = [
        "email",
        "name",
        "password"
    ];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ApiToken", mappedBy="user", orphanRemoval=true)
     */
    private $apiTokens;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deleted_at;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->apiTokens = new ArrayCollection();
        $this->created_at = new \DateTime("now");
        $this->status = GeneralTypes::STATUS_ENABLE;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }

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

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function encryptPassword(UserPasswordEncoderInterface $passwordEncoder)
    {
        $password       = empty($this->password) ? "" : $this->password;
        $this->password = $passwordEncoder->encodePassword($this, $password);
    }

    public function getFullData(): array
    {
        return [
            "id" => $this->getId(),
            "email" => $this->email,
            "status" => $this->status,
            "status_description" => GeneralTypes::getDefaultDescription($this->status),
            "created_at" => $this->getDateTimeStringFrom('created_at'),
            "updated_at" => $this->getDateTimeStringFrom('updated_at')
        ];
    }

    public function getOriginalData(): array
    {
        return [
            "id" => $this->getId(),
            "email" => $this->email,
            "status" => $this->status,
            "created_at" => $this->getDateTimeStringFrom('created_at'),
            "updated_at" => $this->getDateTimeStringFrom('updated_at'),
            "deleted_at" => $this->getDateTimeStringFrom('deleted_at')
        ];
    }

    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function addApiToken(ApiToken $apiToken): self
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens[] = $apiToken;
            $apiToken->setUser($this);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): self
    {
        if ($this->apiTokens->contains($apiToken)) {
            $this->apiTokens->removeElement($apiToken);
            // set the owning side to null (unless already changed)
            if ($apiToken->getUser() === $this) {
                $apiToken->setUser(null);
            }
        }

        return $this;
    }

    public function getLoginData(): array
    {
        return [
            "id" => $this->id,
            "email" => $this->email
        ];
    }

    public function setDisable()
    {
        $this->status = GeneralTypes::STATUS_DISABLE;
    }

    public function getUser(): User
    {
        return $this;
    }

    public function getStatus(): string
    {
        return (string) $this->status;
    }

    /**
     * @throws \Exception
     */
    public function setAttributes(array $values): void
    {
        if ($this->getId()) {
            $this->updateLastUpdated();
        }

        parent::setAttributes($values);
    }

    /**
     * @throws \Exception
     */
    public function delete(): void
    {
        $this->deleted_at = new \DateTime('now');
    }

    public function getName(): string
    {
        return (string)$this->name;
    }

    public function getNameAndId(): array
    {
        $name = $this->name ?? "";

        return [
            "id" => $this->id,
            "name" => $name
        ];
    }

    public function jsonSerialize()
    {
        return $this->getFullData();
    }

    public function canAuthenticate(): bool
    {
        if ($this->status === GeneralTypes::STATUS_BLOCKED ||
            $this->status === GeneralTypes::STATUS_DISABLE ||
            empty($this->id) ||
            !empty($this->deleted_at)){
            return false;
        }

        return true;
    }
}
