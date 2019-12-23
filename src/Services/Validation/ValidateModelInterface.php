<?php

namespace App\Services\Validation;

/**
 * Interface ValidateModelInterface
 * @package App\Services\Validation
 */
interface ValidateModelInterface
{
    /**
     * @param $model
     *
     * @return mixed
     */
    public function validating($model);
}