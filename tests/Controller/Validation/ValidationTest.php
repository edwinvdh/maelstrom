<?php

namespace Controller\Validation;

use AbstractTestCase;
use App\Controller\Validation\Validator;

class ValidationTest extends AbstractTestCase
{
    /**
     * @dataProvider validationDataProvider
     * @param string $userName
     * @param string $userEmail
     * @param string $userPassword
     * @param string $userPasswordRepeat
     * @param string $expected
     */
    public function testValidation(string $userName, string $userEmail, string $userPassword, string $userPasswordRepeat, string $expected)
    {
        $validator = new Validator();
        $actual = $validator->validateRegistration($userName, $userEmail, $userPassword, $userPasswordRepeat);
        $this->assertEquals($expected, $actual);
    }

    public function validationDataProvider(): array
    {
        return [
            'invalidPasswordToShort' => ['name', 'test@test.com', 'password', 'password', Validator::VALIDATE_INVALID_PASSWORD],
            'invalidPasswordUsingCaps' => ['name', 'test@test.com', 'InvalidPassword', 'InvalidPassword', Validator::VALIDATE_INVALID_PASSWORD],
            'invalidPasswordUsingNumber' => ['name', 'test@test.com', 'Invalid123Password', 'Invalid123Password', Validator::VALIDATE_INVALID_PASSWORD],
            'ValidPasswordNonExistingDomain' => ['name', 'test@djhgkjdfhgjkdf.com', 'Th1sP@sswordIsV4lid', 'Th1sP@sswordIsV4lid', Validator::VALIDATE_INVALID_DOMAIN],
            'ValidPasswordExistingDomain' => ['name', 'test@hotmail.com', 'Th1sP@sswordIsV4lid', 'Th1sP@sswordIsV4lid', ''],
        ];
    }
}
