<?php

namespace Repository\Mapper;

use AbstractTestCase;
use App\Repository\Entity\User;
use App\Repository\Mapper\UserMapper;

class UserMapperTest extends AbstractTestCase
{
    public function testUserMapper()
    {
        $array = [
            'userEmail' => 'johnDoe@email.com',
            'userName' => 'johndoe',
            'userPassword' => 'somesecrethash',
            'userIdentifier' => '01234567-1234-1234-1234-123456789abc'
        ];

        $expected = new User();
        $expected->setUserIdentifier('01234567-1234-1234-1234-123456789abc');
        $expected->setUserPassword('somesecrethash');
        $expected->setUserName('johndoe');
        $expected->setUserEmail('johnDoe@email.com');

        $userMapper = UserMapper::mapArrayToObject($array, new User());
        $this->assertEquals($expected, $userMapper);
    }
}
