<?php

namespace App\Repository;

use App\Helper\Encrypt;
use App\Helper\SqlDateTime;
use App\Helper\Uuid;
use App\Infrastructure\DatabaseConnector\DatabaseConnectorInterface;
use App\Infrastructure\PrivateKeyProvider\PrivateKeyProvider;
use App\Repository\Collection\UserCollection;
use App\Repository\Entity\AbstractEntity;
use App\Repository\Entity\User;
use App\Repository\Mapper\UserMapper;
use DateTime;
use Exception;
use PDO;
use PDOException;

class UserRepository extends AbstractRepository
{
    protected string $tableName = 'User';

    protected string $entity = 'App\Repository\Entity\User';

    public function doesTableExist()
    {
        $query = sprintf(
            'SELECT * 
            FROM information_schema.tables
            WHERE table_schema = \'%s\' 
                AND table_name = \'%s\'
            LIMIT 1;',
            $this->databaseConnector->getDatabaseName(),
            $this->tableName
        );

        return (count($this->databaseConnector->getResultsFromRawQuery($query)) > 0);
    }

    /**
     * @return array
     * @throws Mapper\Exception\UserMapperException
     */
    public function getAll(): array
    {
        $query = sprintf('SELECT * FROM %s WHERE deletedOn is :deletedOn', $this->tableName);
        $this->databaseConnector->query($query)->parameter(':deletedOn', '', PDO::PARAM_NULL);

        $collection = new UserCollection();
        $results = $this->executeQuery($this->databaseConnector);

        return $collection->getCollection($results);
    }

    /**
     * @param string $encryptedEmail
     * @return User
     */
    public function getByEncodedEmail(string $encryptedEmail): User
    {
        $query = sprintf('SELECT * FROM %s WHERE userEmail = :userEmail AND deletedOn is :deletedOn', $this->tableName);
        $this->databaseConnector->query($query)
            ->parameter(':userEmail', $encryptedEmail, PDO::PARAM_STR)
            ->parameter(':deletedOn', '', PDO::PARAM_NULL);

        return $this->getUserFromRowSet($this->executeQuery($this->databaseConnector));
    }

    /**
     * @param string $encryptedEmail
     * @param string $encryptedPassword
     * @return User
     */
    public function getByEncodedEmailAndPassword(string $encryptedEmail, string $encryptedPassword): User
    {
        $query = sprintf('SELECT * FROM %s WHERE userEmail = :userEmail AND userPassword = :userPassword AND deletedOn is :deletedOn', $this->tableName);
        $this->databaseConnector->query($query)
            ->parameter(':userEmail', $encryptedEmail, PDO::PARAM_STR)
            ->parameter(':userPassword', $encryptedPassword, PDO::PARAM_STR)
            ->parameter(':deletedOn', '', PDO::PARAM_NULL);

        return $this->getUserFromRowSet($this->executeQuery($this->databaseConnector));
    }

    /**
     * @param string $identifier
     * @return User
     */
    public function getByUuid(string $identifier): User
    {
        $query = sprintf('SELECT * FROM %s WHERE userIdentifier = :userIdentifier AND deletedOn is :deletedOn', $this->tableName);
        $this->databaseConnector->query($query)
            ->parameter(':userIdentifier', $identifier, PDO::PARAM_STR)
            ->parameter(':deletedOn', '', PDO::PARAM_NULL);

        return $this->getUserFromRowSet($this->executeQuery($this->databaseConnector));
    }

    /**
     * Todo: the param types are specific for PDO. this needs to move to the queryBuilder instead.
     * This method will delete the complete record and the history of the record
     * @param string $encryptedEmail
     * @return array
     */
    public function removeRecordByEmail(string $encryptedEmail): array
    {
        // Dependency on PDO right about now... Need fixing
        $query = sprintf('DELETE FROM %s WHERE userEmail = :userEmail', $this->tableName);
        $this->databaseConnector->query($query)
            ->parameter(':userEmail', $encryptedEmail, PDO::PARAM_STR);

        return $this->executeQuery($this->databaseConnector);
    }

    /**
     * This will only set the record deletedOn status.
     * @param User $user
     * @return array
     * @throws Exception
     */
    public function delete(User $user): array
    {
        $query = sprintf('UPDATE  %s set deletedOn = :deletedOn WHERE userIdentifier = :userIdentifier', $this->tableName);
        $this->databaseConnector->query($query)
            ->parameter(':deletedOn', SqlDateTime::getSqlDate(new DateTime()), PDO::PARAM_STR)
            ->parameter(':userIdentifier', $user->getUserIdentifier(), PDO::PARAM_STR);

        $this->databaseConnector->getPdoStatement();

        return $this->executeQuery($this->databaseConnector);
    }

    /**
     * @param string $userIdentifier
     * @param string $encryptedName
     * @param string $encryptedEmail
     * @param string $encryptedPassword
     * @return User
     * @throws Exception
     */
    public function update(string $userIdentifier, string $encryptedName, string $encryptedEmail, string $encryptedPassword): User
    {
        $query = sprintf('UPDATE  %s 
            set userName = :userName, userEmail = :userEmail, userPassword = :userPassword, updatedOn = :updatedOn 
            WHERE userIdentifier = :userIdentifier', $this->tableName);
        $this->databaseConnector->query($query)
            ->parameter(':userName', $encryptedName, PDO::PARAM_STR)
            ->parameter(':userEmail', $encryptedEmail, PDO::PARAM_STR)
            ->parameter(':userPassword', $encryptedPassword, PDO::PARAM_STR)
            ->parameter(':updatedOn',  SqlDateTime::getSqlDate(new DateTime()), PDO::PARAM_STR)
            ->parameter(':userIdentifier', $userIdentifier, PDO::PARAM_STR);

        $this->databaseConnector->getPdoStatement();
        $this->executeQuery($this->databaseConnector);

        return $this->getByUuid($userIdentifier);
    }
    /**
     * @param User $user
     * @return User
     * @throws Exception
     */
    public function create(User $user): User
    {
        $privateKeyProvider = new PrivateKeyProvider(getenv('PRIVATE_KEY'));
        $encryptedEmail = Encrypt::encrypt($privateKeyProvider, $user->getUserEmail());

        $userExists = $this->getByEncodedEmail($encryptedEmail);
        if (!is_null($userExists->getUserIdentifier()) and $userExists->getUserIdentifier()!='') {
            return $userExists;
        }

        $query = sprintf(
            'INSERT INTO  %s  
                (userIdentifier, userEmail, userName, userPassword, createdOn) 
                VALUES 
                (:userIdentifier, :userEmail, :userName, :userPassword, :createdOn)',
            $this->tableName,
        );

        $identifier = $this->getUniqueIdentifier();
        $user->setUserIdentifier($identifier);
        $this->databaseConnector->query($query)
            ->parameter(':userIdentifier', $identifier, PDO::PARAM_STR)
            ->parameter(':userEmail', $encryptedEmail, PDO::PARAM_STR)
            ->parameter(':userName', Encrypt::encrypt($privateKeyProvider, $user->getUserName()), PDO::PARAM_STR)
            ->parameter(':userPassword', Encrypt::encrypt($privateKeyProvider, $user->getUserPassword()), PDO::PARAM_STR)
            ->parameter(':createdOn', SqlDateTime::getSqlDate($user->getCreatedOn()), PDO::PARAM_STR);
        $this->executeQuery($this->databaseConnector);
        return $this->getByEncodedEmail($encryptedEmail);
    }

    /**
     * @return string
     */
    public function getUniqueIdentifier(): string
    {
        $uuid = Uuid::createUuid();
        $user = $this->getByUuid($uuid);
        if (!is_null($user->getUserIdentifier())) {
            $this->getUniqueIdentifier();       // Random seed was already present. Create new one
        }

        return $uuid;
    }

    private function getUserFromRowSet(array $users)
    {
        $return = new User();
        foreach ($users as $userStdObject) {
            $user = new User();
            $return = UserMapper::mapArrayToObject($userStdObject, $user);
        }

        return $return;
    }
}