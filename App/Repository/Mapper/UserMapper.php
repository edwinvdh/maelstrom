<?php

namespace App\Repository\Mapper;

use App\Helper\Request;
use App\Repository\Entity\User;
use App\Repository\Mapper\Exception\UserMapperException;
use DateTime;
use ReflectionClass;

/**
 * Mapper class from SQL object to repository Entities
 */
class UserMapper
{
    /**
     * I am using the reflection class to determine what type the var should be.
     * This could have been done nicer. I know... But I am in a hurry ;)
     *
     * @param array $array
     * @param User $user
     * @return User
     * @throws
     */
    public static function mapArrayToObject(array $array, User $user): User
    {
        $reflection = new ReflectionClass($user);
        foreach ($array as $field => $value) {
            if (!$reflection->hasProperty($field)) {
                continue;
            }

            $method = 'set' . ucfirst($field);
            if (method_exists($user, $method)) {
                $methodReflection = $reflection->getMethod($method);
                $methodParameters = $methodReflection->getParameters();

                // This is where the fun begins...
                foreach ($methodParameters as $methodParameter) {
                    if ($methodParameter->getType() == 'DateTime') {
                        $user->$method(new DateTime($value));
                    } else {
                        $user->$method($value);
                    }
                }
            } else {
                throw new UserMapperException($field);
            }
        }

        return $user;
    }

    public static function mapRequestToUser(Request $request, User $user)
    {
        $user = new User();
        $user->setUserEmail($request->get('userEmail'));
        $user->setUserPassword($request->get('userPassword'));
        $user->setUserName($request->get('userName'));
        return $user;
    }
}
