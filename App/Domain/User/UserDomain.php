<?php

namespace App\Domain\User;

use App\Domain\AggregateRoot;
use App\Helper\Encrypt;
use App\Infrastructure\PrivateKeyProvider\PrivateKeyProvider;
use App\Repository\Entity\User;
use App\Repository\UserRepository;

class UserDomain extends AggregateRoot
{
    protected PrivateKeyProvider $privateKeyProvider;

    public function __construct(UserRepository $repository)
    {
        $this->privateKeyProvider = new PrivateKeyProvider(getenv('PRIVATE_KEY'));
        parent::__construct($repository);
    }

    /**
     * @param string $email
     * @return User
     */
    public function getByEmail(string $email): User
    {
        return $this->repository->getByEncodedEmail(Encrypt::encrypt($this->privateKeyProvider, $email));
    }

    /**
     * @param string $email
     * @param string $password
     * @return User
     */
    public function getByEmailAndPassword(string $email, string $password): User
    {
        return $this->repository->getByEncodedEmailAndPassword(
            Encrypt::encrypt($this->privateKeyProvider, $email),
            Encrypt::encrypt($this->privateKeyProvider, $password)
        );
    }

    /**
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function isValidUser(string $email, string $password)
    {
        $user = $this->getByEmailAndPassword($email, $password);
        return (!is_null($user->getUserIdentifier()));
    }

    /**
     * @param User $user
     * @return User
     */
    public function create(User $user): User
    {
        return $this->repository->create($user);
    }

    /**
     * @param string $userIdentifier
     * @return User
     */
    public function getByUuid(string $userIdentifier): User
    {
        return $this->repository->getByUuid($userIdentifier);
    }

    public function update(User $user)
    {
        $this->repository->update($user->getUserIdentifier(), $user->getUserName(), $user->getUserEmail(), $user->getUserPassword());
    }

    /**
     * Todo: fix return value
     * @param User $user
     * @return array
     */
    public function delete(User $user): array
    {
        return $this->repository->delete($user);
    }
}