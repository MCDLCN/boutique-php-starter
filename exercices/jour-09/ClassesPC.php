<?php

class Product
{
    public function __construct(
        private int $id,
        private string $name,
        private float $price,
        private int $stock,
        private Category $category
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function getCategory(): Category
    {
        return $this->category;
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
        if ($qty < 1) {
            return false;
        }
        return ($currentInCart + $qty) <= $this->stock;
    }
}

class Category
{
    private static array $pool = [];
    private int $count = 0;
    public function __construct(
        private string $name
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function increaseCount(): void
    {
        $this->count++;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public static function fromName(string $name): self
    {
        $key = mb_strtolower(trim($name));
        return self::$pool['key'] ??= new self($name);
    }

    public function __toString(): string
    {
        return $this->name;
    }
}




// $products=[$product1,$product2,$product3,$product4,$product5];
// foreach ($products as $product){
//     echo $product->getName();
//     echo '<br>';
//     echo $product->getCategory();
//     echo '<br>';
// }
