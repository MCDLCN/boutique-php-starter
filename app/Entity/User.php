<?php

namespace App\Entity;

class User
{
    /**
     * Summary of __construct
     * @param int $id
     * @param Address[] $Addresses
     */
    public function __construct(
        private ?int $id,
        private string $name,
        private string $email,
        private string $registrationDate,
        private array $Addresses = []
    ) {
    }
    public function addAddress(Address $address): void
    {
        $this->Addresses[$address->getId()] = $address;
    }

    /**
     * Summary of getAddresses
     * @return Address[]
     */
    public function getAddresses(): array
    {
        return $this->Addresses;
    }

    public function getDefaultAddress(): ?Address
    {
        foreach ($this->Addresses as $address) {
            if ($address->isDefault()) {
                return $address;
            }
        }
        return null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRegistrationDate(): string
    {
        return $this->registrationDate;
    }

    public function removeAddress(int $id): void
    {
        unset($this->Addresses[$id]);
    }

    public function clearAddresses(): void
    {
        $this->Addresses = [];
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
