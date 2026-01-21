<?php

interface RepositoryInterface
{
    public function find(int $id): ?object;
    public function findAll(): array;
    public function save(object $entity): void;
    public function delete(int $id): void;
}

class ProductRepository implements RepositoryInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    public function find(int $id): ?Product
    {
        // À toi : implémente avec une requête préparée
    }

    public function findAll(): array
    {
        // À toi
    }

    public function save(object $entity): void
    {
        // À toi : INSERT ou UPDATE selon si l'entity a un ID
    }

    public function delete(int $id): void
    {
        // À toi
    }
}

// Pour les tests : un faux repository qui ne touche pas à la BDD
class FakeProductRepository implements RepositoryInterface
{
    private array $products = [];

    // À toi : implémente avec un simple tableau en mémoire
}
