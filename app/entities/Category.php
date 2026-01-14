<?php
class Category
{
    private static array $pool = [];
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

    public static function fromName(string $name): self
    {
        $key=mb_strtolower(trim($name));
        return self::$pool['key']??=new self($name);
    }
}
