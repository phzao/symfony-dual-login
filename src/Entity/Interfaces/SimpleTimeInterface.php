<?php

namespace App\Entity\Interfaces;

/**
 * Interface SimpleTimeInterface
 * @package App\Entity\Interfaces
 */
interface SimpleTimeInterface
{
    /**
     * @param string $column
     * @param string $format
     *
     * @return string
     */
    public function getDateTimeStringFrom(string $column, $format = "Y-m-d H:i:s"): string;
}