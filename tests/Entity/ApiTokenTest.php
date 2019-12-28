<?php

namespace App\Tests\Entity;

use App\Entity\ApiToken;
use App\Entity\Interfaces\ApiTokenInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiTokenTest
 * @package App\Tests\Entity
 */
class ApiTokenTest extends TestCase
{
    public function testApiTokenEmpty()
    {
        $apiToken = new ApiToken();
        $this->assertInstanceOf(ApiTokenInterface::class, $apiToken);
        $this->assertCount(4, $apiToken->getDetailsToken());

        $keys = array_keys($apiToken->getDetailsToken());
        $this->assertEquals(["id", "token", "user", "expire_at"], $keys);
        $this->assertInstanceOf(\DateTimeInterface::class, $apiToken->getExpireAt());
        $this->assertIsArray($apiToken->getDetailsToken());
        $this->assertIsString($apiToken->getDateTimeStringFrom(''));
        $this->assertNotEmpty($apiToken->getExpireAt());
        $this->assertNull($apiToken->isValidToken());

        $expire_at = $apiToken->getExpireAt();
        $this->assertIsString($expire_at->format('Y-m-d H:i:s'));
    }
}