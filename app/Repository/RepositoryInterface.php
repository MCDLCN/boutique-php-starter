<?php

namespace App\Repository;

use TEntity;

/**
 * Summary of RepositoryInterface
 * @template TEntity
 */
interface RepositoryInterface
{
    public function find(int $id): ?object;
    /**
     * Summary of findAll
     * @return TEntity[]
     */
    public function findAll(): array;
    public function save(object $entity): void;
    public function delete(int $id): void;
}
