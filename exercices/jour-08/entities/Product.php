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
        public int $discount,
        public string $image,
        public string $date
    ) {
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
        if ($this->stock < $amount) {
            throw new InvalidArgumentException('Not enough in stock');
            ;
        }
        $this->stock -= $amount;
    }

    public function applyDiscount(float $discount): float
    {
        return $this->price - ($this->price * $discount / 100);
    }

    public function isNew(string $dateAdded): bool
    {
        return strtotime($dateAdded) > strtotime('now - 30 day');
    }

    public function isOnSale(int $discount): bool
    {
        return $discount > 0;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price)
    {
        $this->price = $price;
    }

}
