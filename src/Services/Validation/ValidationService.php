<?php

namespace App\Services\Validation;

use App\Utils\HandleErrors\ErrorMessage;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ValidationService
 * @package App\Services\Validation
 */
class ValidationService implements ValidateModelInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * ValidationService constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
    /**
     * @param $model
     *
     * @return void|string
     */
    public function validating($model)
    {
        $errors = $this->validator->validate($model);

        if (!$errors instanceof ConstraintViolationList) {
            return ;
        }

        if ($errors->count() < 1) {
            return;
        }

        $list = [];

        foreach($errors->getIterator() as $error)
        {
            $attribute    = $error->getPropertyPath();
            $messageError = $error->getMessage();
            $list[$attribute] = $messageError;
        }

        $msg = ErrorMessage::getMessageToJson($list);

        throw new UnprocessableEntityHttpException($msg);
    }
}