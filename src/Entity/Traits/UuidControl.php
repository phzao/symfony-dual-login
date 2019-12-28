<?php

namespace App\Entity\Traits;

use Ramsey\Uuid\Uuid;

/**
 * Trait IdControl
 * @package App\Entity\Traits
 */
trait UuidControl
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id()
     * @ORM\Column(name="id", type="uuid", unique=true, options={"default": null})
     */
    protected $id = null;

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @throws \Exception
     */
    public function generateUuid(): void
    {
        $this->id = Uuid::uuid4()->toString();
    }
}