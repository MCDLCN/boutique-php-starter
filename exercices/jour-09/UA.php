<?php
class User{

    public function __construct(
        public string $name,
        public string $email,
        public string $dateInscription,
        public array $Addresses = []
    ){}
        public function addAddress(array $address){
            $this->Addresses[] = $address;
        }

        public function getAddresses(): array{
            return $this->Addresses;
        }

        public function getDefaultAddress(): string{
            return $this->Addresses[0];
        }

}

class Address{

    public function __construct(
        public string $road,
        public string $city,
        public int $postalCode,
        public string $country){}

    public function __toString()
    {
        return $this->road.' '.$this->city.' '.$this->postalCode.' '.$this->country;
    }
}

$add1= new Address("12 aav", "Bordeaux", 46988, "France");
$add2= new Address("784 av", "Paris", 89954, "France");
$add3 = new Address("83 imp", "Los angeles", 4444, "USA");

$user1 = new User("Jean", "jean@exemple.com", time(), [$add1, $add2, $add3]);

foreach  ($user1->getAddresses() as $address){
    echo $address.'<br>';
}
