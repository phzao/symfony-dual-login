<?php

namespace App\Entity\Traits;

trait SimpleTime
{
    /**
     * @param string $column
     * @param string $format
     *
     * @return string
     */
    public function getDateTimeStringFrom(string $column, $format = "d/m/Y H:i:s"): string
    {
        if (empty($this->$column)) {
            return "";
        }
        return $this->$column->format($format);
    }
}