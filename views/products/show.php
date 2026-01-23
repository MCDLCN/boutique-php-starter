<h1><?= e($product->getName()) ?></h1>

<p><?= e($product->getDescription()) ?></p>

<p>
    Price: <?= e((string)$product->getFinalPrice()) ?>
</p>

<?php if ($product->getStock() > 0 and getCart()->getCartItem($product->getId()) !== null):?>
    <form action="/cart/update" method="post">
        <input type="hidden" name="idCart" value="<?= (int)$product->getId() ?>">
        <input type="hidden" name="redirect" value="<?= e('/catalog') ?>">
        <input type="number" name="quantityAdd" value="<?=getCart()->getCartItem($product->getId())->getQuantity()?>" min="1">
        <button type="submit">Add to cart</button>
    </form>
<?php elseif ($product->getStock() > 0 and getCart()->getCartItem($product->getId()) === null):?>
    <form action="/cart/add" method="post">
        <input type="hidden" name="idCart" value="<?= (int)$product->getId() ?>">
        <input type="hidden" name="redirect" value="<?= e('/catalog') ?>">
        <input type="number" name="quantityAdd" value="1" min="1">
        <button type="submit">Add to cart</button>
    </form>
<?php else: ?>
    <p>Out of stock</p>
<?php endif; ?>

<!-- Reviews Section -->
<section class="reviews-section" style="margin-top: 3rem; border-top: 2px solid #eee; padding-top: 2rem;">
    <h2>Reviews & Ratings</h2>

    <!-- Average Rating Display -->
    <?php if (!empty($reviews)): ?>
        <?php
        $averageRating = array_reduce($reviews, fn($carry, $review) => $carry + $review->getRating(), 0) / count($reviews);
        $ratingCount = count($reviews);
        ?>
        <div class="average-rating" style="margin-bottom: 2rem; padding: 1rem; background: #f9f9f9; border-radius: 8px;">
            <p style="margin: 0;">
                <strong style="font-size: 1.5rem;"><?= number_format($averageRating, 1) ?></strong>
                <span style="color: #ffc107; letter-spacing: 0;">
                    <?php 
                    for ($i = 1; $i <= 5; $i++): 
                        if ($i <= floor($averageRating)): 
                            echo '★'; 
                        elseif ($i - 1 < $averageRating && $averageRating - floor($averageRating) >= 0.5):
                            echo '★';
                        else:
                            echo '<span style="color: #ddd;">★</span>';
                        endif;
                    endfor; 
                    ?>
                </span>
                <span style="color: #999;"><?= $ratingCount ?> review<?= $ratingCount !== 1 ? 's' : '' ?></span>
            </p>
        </div>
    <?php endif; ?>

    <!-- Review Form -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <?php if ($userReview === null): ?>
            <div class="review-form" style="margin-bottom: 2rem; padding: 1.5rem; background: #f0f8ff; border-radius: 8px; border-left: 4px solid #007bff;">
                <h3>Write a Review</h3>
                <form id="reviewForm" onsubmit="submitReview(event, <?= $product->getId() ?>)">
                    <div style="margin-bottom: 1rem;">
                        <label for="rating" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Rating</label>
                        <div class="star-rating" id="starContainer" style="display: flex; gap: 0.5rem; font-size: 2rem; flex-direction: row-reverse; width: fit-content;">
                            <input type="radio" name="rating" value="5" id="star5" style="display: none;" required>
                            <label for="star5" class="star-label" data-value="5" style="cursor: pointer; color: #ddd; transition: color 0.2s;">★</label>
                            
                            <input type="radio" name="rating" value="4" id="star4" style="display: none;">
                            <label for="star4" class="star-label" data-value="4" style="cursor: pointer; color: #ddd; transition: color 0.2s;">★</label>
                            
                            <input type="radio" name="rating" value="3" id="star3" style="display: none;">
                            <label for="star3" class="star-label" data-value="3" style="cursor: pointer; color: #ddd; transition: color 0.2s;">★</label>
                            
                            <input type="radio" name="rating" value="2" id="star2" style="display: none;">
                            <label for="star2" class="star-label" data-value="2" style="cursor: pointer; color: #ddd; transition: color 0.2s;">★</label>
                            
                            <input type="radio" name="rating" value="1" id="star1" style="display: none;">
                            <label for="star1" class="star-label" data-value="1" style="cursor: pointer; color: #ddd; transition: color 0.2s;">★</label>
                        </div>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label for="comment" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Your Review</label>
                        <textarea name="comment" id="comment" placeholder="Share your experience with this product..." required style="width: 100%; min-height: 120px; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;"></textarea>
                    </div>

                    <div id="reviewError" style="background-color: #f8d7da; color: #721c24; padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem; display: none;"></div>

                    <button type="submit" style="background-color: #007bff; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">Submit Review</button>
                </form>
            </div>
        <?php else: ?>
            <div class="review-form" style="margin-bottom: 2rem; padding: 1.5rem; background: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
                <h3>Edit Your Review</h3>
                <form id="reviewForm" onsubmit="submitReview(event, <?= $product->getId() ?>)">
                    <div style="margin-bottom: 1rem;">
                        <label for="rating" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Rating</label>
                        <div class="star-rating" id="starContainer" style="display: flex; gap: 0.5rem; font-size: 2rem; flex-direction: row-reverse; width: fit-content;">
                            <input type="radio" name="rating" value="5" id="star5" style="display: none;" required <?= $userReview->getRating() == 5 ? 'checked' : '' ?>>
                            <label for="star5" class="star-label" data-value="5" style="cursor: pointer; color: #ddd; transition: color 0.2s;">★</label>
                            
                            <input type="radio" name="rating" value="4" id="star4" style="display: none;" <?= $userReview->getRating() == 4 ? 'checked' : '' ?>>
                            <label for="star4" class="star-label" data-value="4" style="cursor: pointer; color: #ddd; transition: color 0.2s;">★</label>
                            
                            <input type="radio" name="rating" value="3" id="star3" style="display: none;" <?= $userReview->getRating() == 3 ? 'checked' : '' ?>>
                            <label for="star3" class="star-label" data-value="3" style="cursor: pointer; color: #ddd; transition: color 0.2s;">★</label>
                            
                            <input type="radio" name="rating" value="2" id="star2" style="display: none;" <?= $userReview->getRating() == 2 ? 'checked' : '' ?>>
                            <label for="star2" class="star-label" data-value="2" style="cursor: pointer; color: #ddd; transition: color 0.2s;">★</label>
                            
                            <input type="radio" name="rating" value="1" id="star1" style="display: none;" <?= $userReview->getRating() == 1 ? 'checked' : '' ?>>
                            <label for="star1" class="star-label" data-value="1" style="cursor: pointer; color: #ddd; transition: color 0.2s;">★</label>
                        </div>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label for="comment" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Your Review</label>
                        <textarea name="comment" id="comment" placeholder="Share your experience with this product..." required style="width: 100%; min-height: 120px; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;"><?= e($userReview->getComment()) ?></textarea>
                    </div>

                    <div id="reviewError" style="background-color: #f8d7da; color: #721c24; padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem; display: none;"></div>

                    <button type="submit" style="background-color: #ffc107; color: #333; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">Update Review</button>
                </form>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <p style="padding: 1rem; background: #fff3cd; border-radius: 4px; color: #856404;">
            <a href="#" onclick="openAuthModal('login'); return false;" style="color: #007bff; font-weight: 600;">Log in</a> to write a review
        </p>
    <?php endif; ?>

    <!-- Reviews Display -->
    <div class="reviews-list" style="margin-top: 2rem;">
        <h3>Customer Reviews</h3>
        <?php if (empty($reviews)): ?>
            <p style="color: #999;">No reviews yet. Be the first to review this product!</p>
        <?php else: ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review" style="padding: 1.5rem; border: 1px solid #eee; border-radius: 8px; margin-bottom: 1rem; background: #fafafa;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                        <div>
                            <strong style="font-size: 1.1rem;"><?= e($review->getUser()->getName()) ?></strong>
                            <span style="color: #ffc107; margin-left: 0.5rem; letter-spacing: 0;">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $review->getRating()): ?>
                                        ★
                                    <?php else: ?>
                                        <span style="color: #ddd;">★</span>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </span>
                        </div>
                        <small style="color: #999;"><?= date('M d, Y', strtotime($review->getCreatedDate())) ?></small>
                    </div>
                    <?php if ($review->isEdited()): ?>
                        <small style="color: #999; display: block; margin-bottom: 0.5rem;">(edited <?= date('M d, Y', strtotime($review->getUpdatedDate())) ?>)</small>
                    <?php endif; ?>
                    <p style="margin: 0; color: #333;"><?= e($review->getComment()) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<style>
    #star5:checked ~ label[for="star5"],
    #star4:checked ~ label[for="star4"],
    #star3:checked ~ label[for="star3"],
    #star2:checked ~ label[for="star2"],
    #star1:checked ~ label[for="star1"] {
        color: #ffc107 !important;
    }

    #star5:checked ~ label[for="star5"] ~ label,
    #star4:checked ~ label[for="star4"] ~ label,
    #star3:checked ~ label[for="star3"] ~ label,
    #star2:checked ~ label[for="star2"] ~ label,
    #star1:checked ~ label[for="star1"] ~ label {
        color: #ffc107 !important;
    }

    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #ffc107;
    }
</style>

<script>
    // Star rating interaction
    const starLabels = document.querySelectorAll('.star-label');
    const starContainer = document.getElementById('starContainer');

    starLabels.forEach(label => {
        label.addEventListener('click', function() {
            const value = parseInt(this.dataset.value);
            document.querySelector(`input[value="${value}"]`).checked = true;
            updateStarDisplay();
        });

        label.addEventListener('mouseenter', function() {
            const value = parseInt(this.dataset.value);
            highlightStars(value);
        });
    });

    starContainer.addEventListener('mouseleave', function() {
        updateStarDisplay();
    });

    function highlightStars(rating) {
        starLabels.forEach(label => {
            const value = parseInt(label.dataset.value);
            if (value <= rating) {
                label.style.color = '#ffc107';
            } else {
                label.style.color = '#ddd';
            }
        });
    }

    function clearStars() {
        starLabels.forEach(label => {
            label.style.color = '#ddd';
        });
    }

    function updateStarDisplay() {
        const checked = document.querySelector('input[name="rating"]:checked');
        if (checked) {
            highlightStars(parseInt(checked.value));
        } else {
            clearStars();
        }
    }

    function submitReview(e, productId) {
        e.preventDefault();
        
        const rating = document.querySelector('input[name="rating"]:checked');
        const comment = document.getElementById('comment').value;
        const errorEl = document.getElementById('reviewError');
        
        if (!rating) {
            errorEl.textContent = 'Please select a rating';
            errorEl.style.display = 'block';
            return;
        }

        const formData = new FormData();
        formData.append('rating', rating.value);
        formData.append('comment', comment);

        fetch(`/product/${productId}/review`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Review submitted successfully!');
                location.reload();
            } else {
                errorEl.textContent = data.message || 'Failed to submit review';
                errorEl.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errorEl.textContent = 'An error occurred';
            errorEl.style.display = 'block';
        });
    }
</script>