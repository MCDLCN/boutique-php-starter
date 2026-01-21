<?php

class Category
{
    public function __construct(
        private int $id,
        private string $name,
        private string $description
    ) {
    }

    public function getSlug(): string
    {
        return strtolower(str_replace(' ', '-', $this->name));
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }
}
