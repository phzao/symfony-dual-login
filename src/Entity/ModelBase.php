<?php

namespace App\Entity;

/**
 * Class ModelBase
 * @package App\Entity
 */
class ModelBase
{
    /**
     * @var array
     */
    protected  $attributes = [];

    /**
     * @param array $values
     */
    public function setAttributes(array $values): void
    {
        if (empty($values) ||
            !$this->attributes ||
            count($this->attributes) < 1) {
            return ;
        }

        foreach ($this->attributes as $attribute)
        {
            if (!array_key_exists($attribute, $values)) {
                continue;
            }

            if (!property_exists($this, $attribute)) {
                continue;
            }

            $this->setAttribute($attribute, $values[$attribute]);
        }
    }

    /**
     * @param string $key
     * @param        $value
     */
    public function setAttribute(string $key, $value): void
    {
        $this->$key = $value;
    }

    /**
     * @return array
     */
    public function getFullData(): array
    {
        return [];
    }
}