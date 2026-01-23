<?php

namespace App\Entity;

class Review
{
    public function __construct(
        public int $rating,
        public string $comment,
        public string $createdDate,
        public Product $product,
        public User $user,
        public ?int $id = null,
        public ?string $updatedDate = null,
        public bool $isEdited = false
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getCreatedDate(): string
    {
        return $this->createdDate;
    }

    public function getUpdatedDate(): ?string
    {
        return $this->updatedDate;
    }

    public function isEdited(): bool
    {
        return $this->isEdited;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function setUpdatedDate(string $updatedDate): void
    {
        $this->updatedDate = $updatedDate;
        $this->isEdited = true;
    }
}
