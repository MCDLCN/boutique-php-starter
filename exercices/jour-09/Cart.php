<?php

class CartItem {
    public function __construct(
        public Product $item,
        public int $quantity = 1
    ) {}

    public function setQuantity(int $q): void {
        $this->quantity = max(1, $q);
    }

    public function getTotal(): float {
        return $this->item->getPrice() * $this->quantity;
    }
}

class Cart {
    /** @var array<int, CartItem> */
    private array $items = [];
    public function __construct()
    {}
     public function add(Product $product, int $quantity = 1): self
    {
        $id = $product->getId();

        if (isset($this->items[$id])) {
            $current = $this->items[$id]->getQuantity();
            $this->items[$id]->setQuantity($current + $quantity);
        } else {
            $this->items[$id] = new CartItem($product, $quantity);
        }

        return $this;
    }

    public function remove(int $productId): self
    {
        unset($this->items[$productId]);
        return $this;
    }

    public function update(int $itemId, int $quantity): void {
        if (!isset($this->items[$itemId])) return;
        $this->items[$itemId]->setQuantity($quantity);
    }

    public function getTotal(): float {
        $total = 0.0;
        foreach ($this->items as $cartItem) {
            $total += $cartItem->getTotal();
        }
        return $total;
    }

    public function count(): int {
        return count($this->items); // unique items
    }

    public function clear(): self
    {
        $this->items = [];
        return $this;
    }

    public function getItems(): array {
        return $this->items;
    }

    public function getTotalAllItems(): int {
        $total=0;
        foreach ($this->items as $item){
            $total += $item->quantity;
        }
        return $total;
    }
}
