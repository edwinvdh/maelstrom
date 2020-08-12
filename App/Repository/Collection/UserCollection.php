<?php

namespace App\Repository\Collection;

use App\Helper\Encrypt;
use App\Infrastructure\PrivateKeyProvider\PrivateKeyProvider;
use App\Repository\Entity\User;
use App\Repository\Mapper\Exception\UserMapperException;
use App\Repository\Mapper\UserMapper;

class UserCollection
{
    /**
     * @param array $collectionItems
     * @return array
     * @throws UserMapperException
     */
    public function getCollection(array $collectionItems): array
    {
        $return = [];
        foreach ($collectionItems as $collectionItem) {
            $user = UserMapper::mapArrayToObject($collectionItem, new User());
            // Decrypt proper fields
            $userEmail = $user->getUserEmail();
            $userName = $user->getUserName();
            $user->setUserEmail(Encrypt::decrypt(new PrivateKeyProvider(getenv('PRIVATE_KEY')), $userEmail));
            $user->setUserName(Encrypt::decrypt(new PrivateKeyProvider(getenv('PRIVATE_KEY')), $userName));
            $return[] = $user;
        }

        return $return;
    }
}
