<?php

namespace App\Utils\Enums;
use App\Utils\HandleErrors\ErrorMessage;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * @package App\Utils\Enums
 */
class GeneralTypes
{
    const STATUS_ENABLE  = "enable";
    const STATUS_BLOCKED = "blocked";
    const STATUS_DISABLE = "disable";

    const DEFAULT_SET = "default";
    const DEFAULT_UNSET = "not_default";

    const STATUS_PENDING  = "pending";
    const STATUS_PAID     = "paid";
    const STATUS_OVERDUE  = "overdue";
    const STATUS_CANCELED = "canceled";

    const STATUS_PAYMENT_BANK_TRANSFER = "bank_transfer";
    const STATUS_PAYMENT_CREDIT_CARD = "credit_card";
    const STATUS_PAYMENT_CASH = "cash";
    const STATUS_PAYMENT_CHECK = "check";
    const STATUS_PAYMENT_CREDIT = "credit";
    const STATUS_PAYMENT_MILES = "miles";

    const STATUS_DEFAULT_LIST = [
        self::STATUS_ENABLE,
        self::STATUS_DISABLE,
        self::STATUS_BLOCKED
    ];

    const STATUS_EXPENSE_LIST = [
        self::STATUS_PENDING,
        self::STATUS_PAID,
        self::STATUS_OVERDUE,
        self::STATUS_CANCELED,
    ];

    const STATUS_PAYMENT_LIST = [
        self::STATUS_PAYMENT_BANK_TRANSFER,
        self::STATUS_PAYMENT_CREDIT,
        self::STATUS_PAYMENT_CREDIT_CARD,
        self::STATUS_PAYMENT_CASH,
        self::STATUS_PAYMENT_CHECK,
        self::STATUS_PAYMENT_MILES
    ];

    const DEFAULT_SETTING_DESCRIPTION = [
        self::DEFAULT_UNSET=>"Não padrão",
        self::DEFAULT_SET=>"Padrão",
    ];

    const STATUS_PAYMENT_DESCRIPTION = [
        self::STATUS_PAYMENT_BANK_TRANSFER => "transferência bancária",
        self::STATUS_PAYMENT_CREDIT_CARD => "cartão de crédito",
        self::STATUS_PAYMENT_CREDIT => "credito",
        self::STATUS_PAYMENT_CASH => "dinheiro",
        self::STATUS_PAYMENT_CHECK => "check",
        self::STATUS_PAYMENT_MILES => "milhas"
    ];

    const STATUS_EXPENSE_DESCRIPTION = [
        self::STATUS_PAID => "pago",
        self::STATUS_PENDING => "pendente",
        self::STATUS_OVERDUE => "em atraso",
        self::STATUS_CANCELED => "cancelada"
    ];

    const STATUS_DESCRIPTION = [
        self::STATUS_ENABLE  => "ativo",
        self::STATUS_DISABLE => "inativo"
    ];

    /**
     * @return array
     */
    static public function getStatusList(): array
    {
        return self::STATUS_DEFAULT_LIST;
    }

    /**
     * @return array
     */
    static public function getExpenseStatusList(): array
    {
        return self::STATUS_EXPENSE_LIST;
    }

    /**
     * @return array
     */
    static public function getPaymentStatusList(): array
    {
        return self::STATUS_PAYMENT_LIST;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    static public function getDefaultDescription(string $key): string
    {
        return (new self)->getDescription($key, self::STATUS_DESCRIPTION);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    static public function getDefaultSettingDescription(string $key): string
    {
        return (new self)->getDescription($key, self::DEFAULT_SETTING_DESCRIPTION);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    static public function getPaymentsDescription(string $key): string
    {
        return (new self)->getDescription($key, self::STATUS_PAYMENT_DESCRIPTION);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    static public function getExpenseDescription(string $key): string
    {
        return (new self)->getDescription($key, self::STATUS_EXPENSE_DESCRIPTION);
    }

    /**
     * @param string $key
     * @param array  $list
     *
     * @return string
     */
    public function getDescription(string $key, array $list): string
    {
        if (!array_key_exists($key, $list)) {
            return $key;
        }

        return $list[$key];
    }

    /**
     * @return array
     */
    static public function getStatusDescriptionList(): array
    {
        return self::STATUS_DESCRIPTION;
    }

    static public function isValidDefaultStatusOrFail(string $status)
    {
        $list = self::STATUS_DEFAULT_LIST;
        (new self)->isValidStatusOrFail($status, $list);
    }

    /**
     * @param string $status
     * @param array  $list
     *
     * @return mixed|bool
     */
    public function isValidStatusOrFail(string $status, array $list): ? bool
    {
        if (!in_array($status, $list)) {

            $list = ["status" => "This status $status is invalid!"];
            $msg  = ErrorMessage::getArrayMessageToJson($list);

            throw new UnprocessableEntityHttpException($msg);
        }

        return true;
    }
}