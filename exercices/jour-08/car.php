<?php
class Car
{
    public function __construct(
        public string $brand,
        public float $model,
        public int $year = date('Y')
    ) {}

    public function getAge(): int{
    	return date('Y') - $this->year;
    }
    public function display(): string{
	 	return $this->brand . ' ' . $this->model . ' ' . $this->year . ' ' . $this->getAge();
    }
}

$firstCar = new Car('Mercedes', 2020);
$secondCar = new Car('BMW', 2019);
$thirdCar = new Car('Audi', 2018);
echo $firstCar->display();
echo'<br>';
echo $secondCar->display();
echo'<br>';
echo $thirdCar->display();