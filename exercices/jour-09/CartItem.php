<?php
require_once __DIR__ ."/ClassesPC.php";
class CarItem{

    public function __construct(
        public Product $product,
        public int $quantity
    ){}
        public function getTotal(): int{
            return $this->product->getPrice*$this->quantity;
        }

        public function incremente(): void{
            $this->quantity=$this->quantity+1;
        }

        public function decremente(): void{
            --$this->quantity;
        }
}

$car = new CarItem($product1, 60);
$car2 = new CarItem($product2,80);

$car->decremente();
$car2->incremente();
echo $car->quantity."<br>";
echo $car2->quantity."<br>";