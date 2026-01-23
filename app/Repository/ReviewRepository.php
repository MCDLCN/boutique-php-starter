<?php

namespace App\Repository;

use App\Entity\Review;
use App\Entity\Product;
use App\Entity\User;
use PDO;

class ReviewRepository implements RepositoryInterface
{
    public function __construct(
        private PDO $pdo,
        private ProductRepository $productRepository,
        private UserRepository $userRepository
    ) {
    }

    private function hydrate(array $data): Review
    {
        $product = $this->productRepository->find($data['product_id']);
        $user = $this->userRepository->find($data['user_id']);

        return new Review(
            rating: (int)$data['rating'],
            comment: $data['comment'],
            createdDate: $data['created_date'],
            product: $product,
            user: $user,
            id: (int)$data['id'],
            updatedDate: $data['updated_date'],
            isEdited: (bool)$data['is_edited']
        );
    }

    public function find(int $id): ?Review
    {
        $stmt = $this->pdo->prepare('SELECT * FROM reviews WHERE id = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->hydrate($data) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM reviews');
        $reviews = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reviews[] = $this->hydrate($data);
        }

        return $reviews;
    }

    public function findByProduct(int $productId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM reviews WHERE product_id = ? ORDER BY created_date DESC');
        $stmt->execute([$productId]);
        $reviews = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reviews[] = $this->hydrate($data);
        }

        return $reviews;
    }

    public function findByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM reviews WHERE user_id = ? ORDER BY created_date DESC');
        $stmt->execute([$userId]);
        $reviews = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reviews[] = $this->hydrate($data);
        }

        return $reviews;
    }

    public function findByProductAndUser(int $productId, int $userId): ?Review
    {
        $stmt = $this->pdo->prepare('SELECT * FROM reviews WHERE product_id = ? AND user_id = ?');
        $stmt->execute([$productId, $userId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->hydrate($data) : null;
    }

    public function save(object $entity): void
    {
        if (!$entity instanceof Review) {
            throw new \InvalidArgumentException('Expected Review object');
        }

        // Validate rating is between 1 and 5
        if ($entity->getRating() < 1 || $entity->getRating() > 5) {
            throw new \InvalidArgumentException('Rating must be between 1 and 5');
        }

        // Check if user already has a review for this product
        $existingReview = $this->findByProductAndUser($entity->getProduct()->getId(), $entity->getUser()->getId());
        if ($existingReview instanceof \App\Entity\Review) {
            throw new \InvalidArgumentException('User already has a review for this product');
        }

        $stmt = $this->pdo->prepare('INSERT INTO reviews (rating, comment, created_date, product_id, user_id) VALUES (?, ?, ?, ?, ?)');

        $stmt->execute([
            $entity->getRating(),
            $entity->getComment(),
            $entity->getCreatedDate(),
            $entity->getProduct()->getId(),
            $entity->getUser()->getId()
        ]);

        $entity->setId((int)$this->pdo->lastInsertId());
    }

    public function update(object $entity): void
    {
        if (!$entity instanceof Review) {
            throw new \InvalidArgumentException('Expected Review object');
        }

        // Validate rating is between 1 and 5
        if ($entity->getRating() < 1 || $entity->getRating() > 5) {
            throw new \InvalidArgumentException('Rating must be between 1 and 5');
        }

        $stmt = $this->pdo->prepare('UPDATE reviews SET rating = ?, comment = ?, updated_date = ?, is_edited = ? WHERE id = ?');

        $stmt->execute([
            $entity->getRating(),
            $entity->getComment(),
            $entity->getUpdatedDate(),
            $entity->isEdited() ? 1 : 0,
            $entity->getId()
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM reviews WHERE id = ?');
        $stmt->execute([$id]);
    }
}
