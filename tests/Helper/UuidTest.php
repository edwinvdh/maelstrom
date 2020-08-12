<?php

namespace Helper;

use AbstractTestCase;
use App\Helper\Uuid;

class UuidTest extends AbstractTestCase
{
    public function testValidation()
    {
        $uuid = '12345678-1234-1234-1234-123456789abc';
        $actual = Uuid::is_valid($uuid);
        $this->assertTrue($actual);
    }

    public function testValidationWithZero()
    {
        $uuid = '00000000-0000-0000-0000-000000000000';
        $actual = Uuid::is_valid($uuid);
        $this->assertTrue($actual);
    }


    public function testInValidationWithZero()
    {
        $uuid = 'theseare-four-numb-erss-invalid00000';
        $actual = Uuid::is_valid($uuid);
        $this->assertFalse($actual);
    }

    public function testUuid()
    {
        $uuid = Uuid::createUuid();
        $actual = Uuid::is_valid($uuid);

        $this->assertTrue($actual);
    }
}
