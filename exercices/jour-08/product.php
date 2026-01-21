<?php

class Product
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public float $price,
        public int $stock,
        public string $category
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
        $this->stock -= $amount;
    }

    public function applyDiscount(float $discount): float
    {
        return $this->price - ($this->price * $discount / 100);
    }
}
