<?php

namespace App\Tests\Utils\HandleErrors;

use App\Utils\HandleErrors\ErrorMessage;
use PHPUnit\Framework\TestCase;

/**
 * Class ErrorMessageTest
 * @package App\Tests\Utils\HandleErrors
 */
class ErrorMessageTest extends TestCase
{
    public function testMessages()
    {
        $msg = ErrorMessage::getErrorMessage('');
        $this->assertJson($msg);
        $this->assertArrayHasKey("status", $this->convertToArray($msg));
        $this->assertArrayHasKey("message", $this->convertToArray($msg));
        $this->assertCount(2, $this->convertToArray($msg));
        $this->assertEmpty($this->convertToArray($msg)["message"]);
        $this->assertEquals('error', $this->convertToArray($msg)["status"]);

        $msg = ErrorMessage::getMessageToJson(["message"=>"An error occurred!"]);
        $this->assertJson($msg);
        $this->assertEquals('{"message":"An error occurred!"}', $msg);
        $this->assertArrayHasKey("message", $this->convertToArray($msg));
        $this->assertEquals("An error occurred!", $this->convertToArray($msg)["message"]);
        $this->assertCount(1, $this->convertToArray($msg));

        $msg = ErrorMessage::getErrorData(["key_from_error"=>"error message"]);
        $this->assertJson($msg);
        $this->assertCount(2,  $this->convertToArray($msg));
        $this->assertArrayHasKey("status", $this->convertToArray($msg));
        $this->assertArrayHasKey("data", $this->convertToArray($msg));
        $this->assertEquals('error', $this->convertToArray($msg)["status"]);
        $this->assertArrayHasKey("key_from_error", $this->convertToArray($msg)["data"]);
        $this->assertEquals("error message", $this->convertToArray($msg)["data"]["key_from_error"]);

    }

    /**
     * @param $json
     *
     * @return array
     */
    public function convertToArray($json): array
    {
        return json_decode($json, true);
    }
}