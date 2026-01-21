<?php

class Car
{
    public function __construct(
        public string $brand,
        public string $model,
        private ?int $year = null
    ) {
        if ($this->year === null) {
            $this->year = (int) date('Y');
        }
    }

    public function getAge(): int
    {
        return (int)date('Y') - $this->year;
    }

    public function display(): string
    {
        return $this->brand . ' ' . $this->model . ' ' . $this->year . ' ' . $this->getAge();
    }
}

$firstCar  = new Car('Mercedes', 'Class A', 2020);
$secondCar = new Car('BMW', 'Serie 3', 2019);
$thirdCar  = new Car('Audi', 'A4', 2018);

echo $firstCar->display() . '<br>';
echo $secondCar->display() . '<br>';
echo $thirdCar->display();
