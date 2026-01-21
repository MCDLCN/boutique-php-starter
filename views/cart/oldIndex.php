<main class="main-content">
    <div class="container">

        <div class="page-header">
            <h1 class="page-title">My cart</h1>
            <p class="page-subtitle"><?= (int)$amountItems; ?> items in your cart</p>
        </div>

        <?php if (!$cart->isEmpty()): ?>
        <div class="cart-layout">
            <div class="cart-table">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart->getItems() as $item): ?>
                            <?php
                                //var_dump($item);
                                $qty = (int)$item->getQuantity();
                            $product = $item->getProduct();
                            ?>
                            <tr>
                                <td>
                                    <div class="cart-item">
                                        <div class="cart-item__image">
                                            <img src="<?= e($product->getImage()) ?>" alt="<?= e($product->getName()) ?>">
                                        </div>
                                        <div>
                                            <div class="cart-item__title"><?= e($product->getname()) ?></div>
                                            <div class="cart-item__category"><?= e($product->getCategory()->getName()) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?= formatPrice($product->getFinalPrice())?>
                                </td>
                                <td>
                                    <form action="/cart/update" method="POST" style="display:inline">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="idCart" value="<?= (int)$product->getId() ?>">
                                        <input type="hidden" name="redirect" value="<?= e('/cart') ?>">
                                        <div class="quantity-selector">
                                            <button type="button" onclick="this.nextElementSibling.stepDown()">−</button>
                                            <input type="number" name="quantityUpdate" value="<?= $qty ?>" min="1" max="<?= (int)$product->getStock() ?>" style="width:50px">
                                            <button type="button" onclick="this.previousElementSibling.stepUp()">+</button>
                                        </div>

                                        <button type="submit">Update</button>
                                    </form>
                                </td>
                                <td>
                                    <span class="cart-item__total"><?= formatPrice($item->getTotal()) ?></span>
                                </td>
                                <td>
                                    <form action="/cart/remove" method="POST" style="display:inline" onsubmit="return confirm('Remove this item?');">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="idRemove" value="<?= (int)$product->getId() ?>">
                                        <button type="submit" class="cart-item__remove" title="Remove">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="cart-actions">
                    <a href="/catalog" class="btn btn--outline">← Continue my shopping</a>
                    <form action="/cart/empty" method="POST" style="display:inline" onsubmit="return confirm('Empty the cart?');">
                        <input type="hidden" name="action" value="emptyCart">
                        <button type="submit" class="btn btn--danger">🗑️ Empty the cart</button>
                    </form>
                </div>
            </div>

            <aside class="cart-summary">
                <h3 class="cart-summary__title">Summing up</h3>

                <div class="cart-summary__row">
                    <span>Sub-total</span>
                    <span><?= formatPrice($totalCart) ?></span>
                </div>
                <div class="cart-summary__row">
                    <span>VAT (20%)</span>
                    <span><?= formatPrice($totalCart * 0.2) ?></span>
                </div>
                <div class="cart-summary__row">
                    <span>Delivery</span>
                    <?php if ($freeDelivery): ?>
                        <span style="color: #22c55e;">Free</span>
                    <?php else: ?>
                        <span style="color: #C52289;">5,99$</span>
                    <?php endif; ?>
                </div>
                <div class="cart-summary__row cart-summary__row--total">
                    <span>Total ATI</span>
                    <span><?= formatPrice($totalCart + ($totalCart * 0.2) + ($freeDelivery ? 0 : 5.99)) ?></span>
                </div>

                <div class="cart-summary__checkout">
                    <?php if (isset($_SESSION['auth'])): ?>
                        <a href="/checkout" class="btn btn--primary btn--block btn--lg">Proceed to payment</a>
                    <?php else: ?>
                        <a href="/login" class="btn btn--primary btn--block btn--lg">You need to be logged in to proceed</a>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
        <?php else: ?>
            <div class="cart-empty">
                <div class="cart-empty__icon">🛒</div>
                <h2>Your cart is empty</h2>
                <p>Discover our products and add them to your cart</p>
                <a href="/catalog" class="btn btn--primary btn--lg mt-md">Check catalog</a>
            </div>
        <?php endif; ?>

    </div>
</main>