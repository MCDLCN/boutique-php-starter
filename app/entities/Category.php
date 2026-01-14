<?php
class Category
{
    private int $count = 0;
    public function __construct(
        private string $name
    ) {}
    
    public function getName(): string
    {
        return $this->name;
    }

    public function increaseCount(): void
    {
        $this->count++;  
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
