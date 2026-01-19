<?php
class User{

    public function __construct(
        private ?int $id,
        private string $name,
        private string $email,
        private string $dateInscription,
        private array $Addresses = []
    ){}
        public function addAddress(Address $address){
            $this->Addresses[$address->getId()] = $address;
        }

        public function getAddresses(): array{
            return $this->Addresses;
        }

        public function getDefaultAddress(): ?Address{
            foreach($this->Addresses as $address){
                if($address->isDefault()){
                    return $address;
                }
            }
            return null;
        }

        public function getName(): string{
            return $this->name;
        }

        public function getEmail(): string{
            return $this->email;
        }

        public function getDateInscription(): string{
            return $this->dateInscription;
        }

        public function removeAddress(int $id){
            unset($this->Addresses[$id]);
        }

        public function clearAddresses(): void{
            $this->Addresses = [];
        }

        public function setId(int $id): void{
            $this->id = $id;
        }

        public function getId(): int{
            return $this->id;
        }
}