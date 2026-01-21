<?php

interface Purchasable
{
    public function getId(): int;
    public function getPrice(): float;
    public function getName(): string;
}

class Product implements Purchasable, Displayable, Taxable
{
    // OBLIGÉ d'implémenter toutes les méthodes de l'interface
    public function __construct(
        private int $id,
        private float $price,
        private string $name
    ) {
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getPrice(): float
    {
        return $this->price;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function display(): string
    {
        return $this->name;
    }

    public function getTaxRate(): float
    {
        return 1.1;
    }
}

class GiftCard implements Purchasable, Displayable, Taxable
{
    // Même interface, implémentation différente
    public function __construct(
        private int $id,
        private float $price,
        private string $name
    ) {
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getPrice(): float
    {
        return $this->price;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function display(): string
    {
        return $this->name;
    }
    public function getTaxRate(): float
    {
        return 1.1;
    }
}

// Le panier accepte n'importe quel Purchasable
class Cart
{
    public function __construct(
        private array $items
    ) {
    }
    public function add(Purchasable $item): void
    {
        $this->items[] = $item;
    }
}

interface Displayable
{
    public function display(): string;
}

interface Taxable
{
    public function getTaxRate(): float;
}
