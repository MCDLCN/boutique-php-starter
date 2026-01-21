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