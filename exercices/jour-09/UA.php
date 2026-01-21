<?php

class User
{
    public function __construct(
        public string $name,
        public string $email,
        public string $dateInscription,
        public array $Addresses = []
    ) {
    }
    public function addAddress(array $address)
    {
        $this->Addresses[] = $address;
    }

    public function getAddresses(): array
    {
        return $this->Addresses;
    }

    public function getDefaultAddress(): string
    {
        return $this->Addresses[0];
    }

}

class Address
{
    public function __construct(
        public string $road,
        public string $city,
        public int $postalCode,
        public string $country
    ) {
    }

    public function __toString()
    {
        return $this->road.' '.$this->city.' '.$this->postalCode.' '.$this->country;
    }
}
