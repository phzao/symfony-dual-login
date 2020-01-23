<?php

namespace App\Utils\Datetime;

use App\Utils\Datetime\Interfaces\DatetimeCheckServiceInterface;
use App\Utils\HandleErrors\ErrorMessage;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class DatetimeCheckService implements DatetimeCheckServiceInterface
{
    /**
     * @var \DateTime
     */
    private $now;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->now = new \DateTime('now');
    }

    public function thisDatetimeIsGreaterThanNow(\DateTimeInterface $datetime): bool
    {
        if ($this->now < $datetime) {
            return true;
        }

        return false;
    }

    /**
     * @throws \Exception
     */
    public function getDatesListConvertedToDatetimeOrFail(array $attributeList,
                                                          array $params): array
    {
        if (empty($attributeList) || empty($params)) {
            return $params;
        }

        foreach ($attributeList as $attribute=>$value)
        {
            if (empty($params[$attribute])) {
                continue;
            }

            $newDate = \DateTime::createFromFormat($value["format"], $params[$attribute]);

            if ($newDate===FALSE) {
                $error[$attribute] = $value["message"];
                $msg = ErrorMessage::getArrayMessageToJson($error);

                throw new UnprocessableEntityHttpException($msg);
            }

            $params[$attribute] = $newDate;
        }

        return $params;
    }

    /**
     * @throws \Exception
     */
    public function getMonthInTheFutureFrom(\DateTime $dateTime, int $num_month_ahead): \DateTime
    {
        $newDate = new \DateTime($dateTime->format('Y-m-d'));
        $newDate->modify("+$num_month_ahead month");

        return $newDate;
    }
}