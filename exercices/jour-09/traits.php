<?php

trait Timestampable
{
    private ?DateTime $createdAt = null;
    private ?DateTime $updatedAt = null;

    public function setCreatedAt(): void
    {
        $this->createdAt = new DateTime();
    }

    public function setUpdatedAt(): void
    {
        $this->updatedAt = new DateTime();
    }

    // À toi : ajoute les getters
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }
}

class Product
{
    use Timestampable; // "Importe" toutes les méthodes du trait
    use Sluggable;
    use Sluggish;
    public function __construct(public string $name)
    {
        $this->setCreatedAt();
    }
}

class User
{
    use Timestampable; // Même code, autre classe !
    use Sluggable;
    public function __construct(public string $name)
    {
        $this->setCreatedAt();
    }
}
trait Sluggable
{
    public function getSlug(): string
    {
        return strtolower(str_replace(' ', '-', $this->name));
    }
}

trait Sluggish
{
    public function getSlug(): string
    {
        return strtolower(str_replace(' ', '-', $this->name));
    }
}

$product = new Product('AAA A AAAAAA   AAAAA');
//not executed because collision
echo $product->getSlug();
