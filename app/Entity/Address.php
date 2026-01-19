<?php
class Address{

    public function __construct(
        private ?int $id,
        private int $userId,
        private string $road,
        private string $city,
        private string $postalCode,
        private string $country,
        private bool $isDefault
        ){}
        

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

    public function getPostalCode(): string{
        return $this->postalCode;
    }
    public function getCountry(): string{
        return $this->country;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getUserId(): int{
        return $this->userId;
    }

    public function isDefault(): bool{
        return $this->isDefault;
    }
}