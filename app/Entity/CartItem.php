<?php

namespace App\Entity;

class CartItem
{
    public function __construct(
        private Product $product,
        private int $quantity = 1
    ) {
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = max(1, $quantity);
    }

    public function getTotal(): float
    {
        return $this->product->getFinalPrice() * $this->quantity;
    }
}
