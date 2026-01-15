<?php
class Product {
    public function __construct(
        protected string $name,
        protected float $price
    ) {}
    
    public function getPriceTTC(): float {
        return $this->price * 1.2;
    }
}

class DigitalProduct extends Product {
    
    public function __construct(
        string $name,
        float $price,
        private string $downloadUrl
    ) {
        parent::__construct($name, $price); // Appelle le constructeur parent
    }
    
    public function getDownloadLink(): string {
        return $this->downloadUrl;
    }
    #[\Override]
    public function getPriceTTC(): float{
        return $this->price * 1.1;
    }
    // À toi : ajoute une méthode getDownloadLink()
    // À toi : override getPriceTTC() pour une TVA différente (si applicable)
}

class PhysicalProduct extends Product {

    public function __construct(
        string $name,
        float $price,
        private float $weight,
        private array $dimensions =[])
        {}
    
    public function getShippingCost(): float{
        if ($this->weight>100){
            return 50;
        }
        if ($this->weight>50){
            return 25;
        }
        else{
            return 0;
        }
    }

}
$ebook = new DigitalProduct('Guide PHP', 19.99, 'https://...');