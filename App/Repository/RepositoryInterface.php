<?php

namespace App\Repository;

use App\Repository\Entity\User;

interface RepositoryInterface
{
    public function getAll(): array;

    public function getByEncodedEmail(string $encryptedEmail): User;

    public function getByEncodedEmailAndPassword(string $encryptedEmail, string $encryptedPassword): User;

    public function getByUuid(string $identifier): User;

    public function delete(User $user): array;

    public function create(User $user): User;

    public function update(string $userIdentifier, string $encryptedName, string $encryptedEmail, string $encryptedPassword): User;

    public function getUniqueIdentifier(): string;

    // This method is specific for userRepository
    public function removeRecordByEmail(string $encryptedEmail): array;
}
