<?php

namespace App\Repository\Entity;

use DateTime;
use Exception;

/**
 * Definition on what the table should have.
 */
abstract class AbstractEntity
{
    /** @var int */
    public int $id;

    public DateTime $updatedOn;

    public DateTime $deletedOn;

    public DateTime $createdOn;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return AbstractEntity
     */
    public function setId(int $id): AbstractEntity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedOn(): DateTime
    {
        return $this->updatedOn;
    }

    /**
     * @param DateTime $updatedOn
     * @return AbstractEntity
     */
    public function setUpdatedOn(DateTime $updatedOn): AbstractEntity
    {
        $this->updatedOn = $updatedOn;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDeletedOn(): DateTime
    {
        return $this->deletedOn;
    }

    /**
     * @param DateTime $deletedOn
     * @return AbstractEntity
     */
    public function setDeletedOn(DateTime $deletedOn): AbstractEntity
    {
        $this->deletedOn = $deletedOn;
        return $this;
    }

    /**
     * @return DateTime
     * @throws Exception
     */
    public function getCreatedOn(): DateTime
    {
        return new DateTime();
    }

    /**
     * @param DateTime $createdOn
     * @return AbstractEntity
     */
    public function setCreatedOn(DateTime $createdOn): AbstractEntity
    {
        $this->createdOn = $createdOn;
        return $this;
    }
}