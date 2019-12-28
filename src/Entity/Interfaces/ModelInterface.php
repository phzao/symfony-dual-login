<?php

namespace App\Entity\Interfaces;

/**
 * Interface ModelInterface
 */
interface ModelInterface
{
    /**
     * @param array $values
     */
    public function setAttributes(array $values): void;

    /**
     * @param string $key
     * @param        $value
     */
    public function setAttribute(string $key, $value): void;

    /**
     * @return array
     */
    public function getFullData(): array;

    /**
     * @return null|string
     */
    public function getId(): ?string;

    public function generateUuid(): void;
}