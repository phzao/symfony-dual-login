<?php

namespace App\Utils\Enums;
/**
 * Class GeneralTypes
 */
abstract class GeneralTypes
{
    const STATUS_ENABLE  = "enable";
    const STATUS_BLOCKED = "blocked";
    const STATUS_DISABLE = "disable";

    const STATUS_LIST    = [
        self::STATUS_ENABLE,
//        self::STATUS_BLOCKED,
        self::STATUS_DISABLE
    ];

    const STATUS_DESCRIPTION = [
        self::STATUS_ENABLE  => "ativo",
//        self::STATUS_BLOCKED => "bloqueado",
        self::STATUS_DISABLE => "inativo"
    ];

    /**
     * @return array
     */
    static public function getStatusList(): array
    {
        return self::STATUS_LIST;
    }

    /**
     * @return array
     */
    static public function getStatusDescriptionList(): array
    {
        return self::STATUS_DESCRIPTION;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    static public function getStatusDescription(string $key): string
    {
        if (!in_array($key, self::getStatusList())) {
            return $key;
        }

        return self::STATUS_DESCRIPTION[$key];
    }
}