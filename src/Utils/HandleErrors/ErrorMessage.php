<?php

namespace App\Utils\HandleErrors;

/**
 * Class ErrorMessage
 * @package App\Utils\HandleErrors
 */
final class ErrorMessage
{
    /**
     * @param string $message
     * @param string $status
     * @param array $messageList
     * @param string $messageKey
     * @return string
     */
    public static function getErrorMessage(string $message,
                                           string $status = "error",
                                           $messageList = [],
                                           string $messageKey ="message"): string
    {
        $errormsg["status"]    = $status;
        $errormsg[$messageKey] = empty($message) ? $messageList : $message;
        return json_encode($errormsg);
    }
    /**
     * @param array  $messageList
     * @param string $messageKey
     *
     * @return string
     */
    public static function getErrorData(array $messageList, string $messageKey = "data"): string
    {
        return self::getErrorMessage("", "error", $messageList, $messageKey);
    }
    /**
     * @param array $messageList
     *
     * @return string
     */
    public static function getMessageToJson(array $messageList): string
    {
        return json_encode($messageList);
    }
}