<?php

namespace App\Entity;

class Cart
{
    /** @var CartItem[] */
    private array $items = [];

    public function addProduct(Product $product, int $quantity = 1): void
    {
        $id = $product->getId();

        if (isset($this->items[$id])) {
            // Produit déjà dans le panier → augmenter quantité
            $currentQuantity = $this->items[$id]->getQuantity();
            $this->items[$id]->setQuantity($currentQuantity + $quantity);
        } else {
            // Nouveau produit
            $this->items[$id] = new CartItem($product, $quantity);
        }
    }

    public function removeProduct(int $productId): void
    {
        unset($this->items[$productId]);
    }

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getTotal();
        }
        return $total;
    }

    public function countUnique(): int
    {
        return count($this->items);
    }

    public function clear(): void
    {
        $this->items = [];
    }

    public function getCartItem(int $id): ?CartItem
    {
        return $this->items[$id] ?? null;
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    public function setProduct(Product $product): void
    {
        $id = $product->getId();
        if (!isset($this->items[$id])) {
            return;
        }
        $this->items[$id] = new CartItem($product, $this->items[$id]->getQuantity());
    }

    public function countAllItems(): int
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getQuantity();
        }
        return $total;
    }
}
