<?php

class Category
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public ?string $description = null
    ) {
        $this->slug = $this->generateSlug($name);
    }

    private function generateSlug(string $name): string
    {
        // Convert to lowercase, remove special characters, replace spaces with hyphens
        $slug = strtolower($name);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}