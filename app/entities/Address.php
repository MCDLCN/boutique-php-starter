<?php
class Address{

    public function __construct(
        private int $id,
        private string $road,
        private string $city,
        private int $postalCode,
        private string $country){}

    public function __toString(): string
    {
        return $this->road.', '.$this->city.', '.$this->postalCode.', '.$this->country;
    }


    public function getRoad(): string{
        return $this->road;
    }

    public function getCity(): string{
        return $this->city;
    }

    public function getPostalCode(): int{
        return $this->postalCode;
    }
    public function getCountry(): string{
        return $this->country;
    }

    public function getId(): int{
        return $this->id;
    }
}