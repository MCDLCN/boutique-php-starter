<?php

class Product
{
    public function __construct(
        private int $id,
        public string $name,
        public string $description,
        private float $price,
        public int $stock,
        public string $category,
        private int $discount,
        private string $image,
        private string $dateAdded
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getDiscount(): int
    {
        return $this->discount;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getDateAdded(): string
    {
        return $this->dateAdded;
    }

    public function getPriceIncludingTax(float $vat = 20): float
    {
        return $this->price + ($this->price * $vat / 100);
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function reduceStock(int $amount): void
    {
        if ($amount < 1) {
            throw new InvalidArgumentException("Amount must be >= 1");
        }
        if ($this->stock < $amount) {
            throw new InvalidArgumentException("Not enough in stock");
        }
        $this->stock -= $amount;
    }

    public function applyDiscount(float $discount): float
    {
        return $this->price * (1 - ($discount / 100));
    }

    public function isNew(): bool
    {
        return strtotime($this->dateAdded) > strtotime('-30 days');
    }

    public function isOnSale(): bool
    {
        return $this->discount > 0;
    }

    public function getFinalPrice(): float
    {
        return $this->isOnSale()
            ? $this->applyDiscount($this->discount)
            : $this->price;
    }
   	public function canAddToCart(int $qty, int $currentInCart = 0): bool
    {
        if ($qty < 1) return false;
        return ($currentInCart + $qty) <= $this->stock;
    }
}