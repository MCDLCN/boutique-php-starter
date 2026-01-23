<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\ReviewRepository;

class ReviewController extends Controller
{
    public function __construct(private ReviewRepository $reviewRepository)
    {
    }

    public function listByProduct(int $productId): array
    {
        return $this->reviewRepository->findByProduct($productId);
    }

    public function getUserReviews(int $userId): array
    {
        return $this->reviewRepository->findByUser($userId);
    }

    public function getReview(int $reviewId): ?Review
    {
        return $this->reviewRepository->find($reviewId);
    }

    public function create(Review $review): void
    {
        $this->reviewRepository->save($review);
    }

    public function updateReview(Review $review): void
    {
        $this->reviewRepository->update($review);
    }

    public function deleteReview(int $reviewId): void
    {
        $this->reviewRepository->delete($reviewId);
    }
}
