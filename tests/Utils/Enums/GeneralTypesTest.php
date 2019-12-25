<?php

namespace App\Tests\Utils\Enums;

use App\Utils\Enums\GeneralTypes;
use PHPUnit\Framework\TestCase;

/**
 * Class GeneralTypesTest
 * @package App\Tests\Utils\Enums
 */
class GeneralTypesTest extends TestCase
{
    public function testStatusTypes()
    {
        $list = GeneralTypes::getStatusList();

        $this->assertCount(2, $list);
        $this->assertEquals(["enable", "disable"], $list);

        $list = GeneralTypes::getStatusDescriptionList();
        $descriptions = [
            "enable"  => "ativo",
            "disable" => "inativo"
        ];
        $this->assertCount(2, $list);
        $this->assertEquals($descriptions, $list);
    }
}