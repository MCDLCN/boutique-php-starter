<?php

namespace App\Repository;
use App\Entity\Product;

interface RepositoryInterface
{
    public function find(int $id): ?object;
    /**
     * Summary of findAll
     * @return Product[]
     */
    public function findAll(): array;
    public function save(object $entity): void;
    public function delete(int $id): void;
}
