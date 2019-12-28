<?php

namespace App\Tests\Entity\Traits;

use App\Entity\Traits\SimpleTime;
use App\Entity\Traits\UuidControl;
use PHPUnit\Framework\TestCase;

/**
 * Class UuidControlTest
 * @package App\Tests\Entity\Traits
 */
class UuidControlTest extends TestCase
{
    use UuidControl;

    protected $id;

    public function testUuid()
    {
        $this->assertNull($this->id);
        $this->assertEmpty($this->getId());
        $this->assertEmpty($this->generateUuid());
        $this->assertIsString($this->id);
    }
}