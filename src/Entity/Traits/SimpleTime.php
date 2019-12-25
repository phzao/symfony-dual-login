<?php

namespace App\Entity\Traits;

/**
 * Trait SimpleTime
 * @package App\Entity\Traits
 */
trait SimpleTime
{
    /**
     * @param string $column
     * @param string $format
     *
     * @return string
     */
    public function getDateTimeStringFrom(string $column, $format = "Y-m-d H:i:s"): string
    {
        if (empty($this->$column)) {
            return "";
        }

        if (!$this->$column instanceof \DateTime) {
            return $this->$column;
        }

        return $this->$column->format($format);
    }
}