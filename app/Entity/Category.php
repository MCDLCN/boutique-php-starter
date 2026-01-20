<?php
namespace App\Entity;
class Category
{
    public function __construct(
        private ?int $id,
        private string $name
    ) {}

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }
}