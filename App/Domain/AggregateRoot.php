<?php

namespace App\Domain;

use App\Repository\RepositoryInterface;

abstract class AggregateRoot
{
    protected RepositoryInterface $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->repository->getAll();
    }
}