<?php
class Product {
    public function __construct(
        private string $name,
        private float $price
    ) {}
    
    // Appelée par echo $product ou (string)$product
    public function __toString(): string {
        return "{$this->name} ({$this->price} €)";
    }
    
    // Appelée quand on accède à une propriété inexistante
    public function __get(string $name): mixed {
        if ($name === 'priceTTC') {
            return $this->price * 1.2;
        }
        throw new Exception("Propriété $name inexistante");
    }

    public function __isset(string $name): bool {
        if ($name === 'priceTTC') {   
            return true;
        }
        return false;
    }

    public function __set(string $name, mixed $value): void {
        if (property_exists($this, $name)) {   
            throw new Exception("$name is a read-only property");
        }
    }
}

$p = new Product('T-shirt', 100);
echo $p;           // "T-shirt (100 €)"
echo '<br>';
echo $p->priceTTC; // 120 (propriété calculée !)

// À toi : ajoute __isset() pour que isset($p->priceTTC) retourne true
echo '<br>';
echo isset($p->priceTTC);
echo '<br>';
$p->price = 100;