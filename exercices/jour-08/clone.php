<?php
class Product {
    public function __construct(
        private int $id,
        public string $name,
        public float $price
    ) {}

    public function __clone(): void {
        $this->name = 'Clone of ' . $this->name;
        $this->id = 100+$this->id;
    }

    public function duplicate(): Product {
        return clone $this;   
    }
}

$original = new Product(1,'T-shirt', 29.99);

// ⚠️ ATTENTION : ce n'est PAS une copie !
$reference = $original;
$reference->price = 99.99;
echo $original->price; // 99.99 ! L'original est modifié !
echo '<br>';
echo $reference->price; // 99.99
echo '<br>';
// ✅ CLONE : vraie copie indépendante
$copie = clone $original;
$copie->price = 19.99;
echo $original->price; // 99.99, l'original est intact
echo '<br>';
echo $copie->price;

echo '<br>';
$copie2 = $original->duplicate();
echo $copie2->name;
echo '<br>';
echo $original->name;
