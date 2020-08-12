<?php

namespace App\Repository\Entity;

class User extends AbstractEntity
{
    protected string $userEmail;

    protected string $userName;

    protected string $userPassword;

    protected ?string $userIdentifier = null;

    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    /**
     * @param string $userEmail
     * @return User
     */
    public function setUserEmail(string $userEmail): User
    {
        $this->userEmail = $userEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     * @return User
     */
    public function setUserName(string $userName): User
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserPassword(): string
    {
        return $this->userPassword;
    }

    /**
     * @param string $userPassword
     * @return User
     */
    public function setUserPassword(string $userPassword): User
    {
        $this->userPassword = $userPassword;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserIdentifier(): ?string
    {
        return $this->userIdentifier;
    }

    /**
     * @param string $userIdentifier
     * @return User
     */
    public function setUserIdentifier(?string $userIdentifier): User
    {
        $this->userIdentifier = $userIdentifier;
        return $this;
    }
}