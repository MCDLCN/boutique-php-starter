<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../app/data.php';
require_once __DIR__ . '/../app/helpers.php';


$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$product = null;
foreach ($products as $p) {
    if (isset($p['id']) && (int)$p['id'] === $id) {
        $product = $p;
        break;
    }
}

if ($product === null) {
    http_response_code(404);
}

$category = $product['category'] ?? '';
$name = $product['name'] ?? 'Product not found';
$description = $product['description'] ?? '';
$image = $product['image'] ?? '';
$price = (float)($product['price'] ?? 0);
$discount = (int)($product['discount'] ?? 0);
$stock = (int)($product['stock'] ?? 0);
$dateAdded = $product['dateAdded'] ?? null;


$priceToShow = $price;
$oldPriceToShow = null;

if ($product !== null && function_exists('isOnSale') && isOnSale($discount)) {
    // calculateDiscounted(price, discount) exists in your project
    if (function_exists('calculateDiscounted')) {
        $priceToShow = (float)calculateDiscounted($price, $discount);
        $oldPriceToShow = $price;
    }
}

// Stock label (your displayStock returns [text, colour])
$stockBadgeHtml = '';
if ($product !== null && function_exists('displayStock')) {
    [$stockText, $stockColour] = displayStock($stock);
    if (function_exists('displayBadge')) {
        $stockBadgeHtml = displayBadge($stockText, $stockColour);
    }
}

$esc = function(string $s): string {
    return function_exists('e') ? e($s) : htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $product === null ? 'Product doesnt exist' : e($name) ?> - <?= defined('WEBSITE_NAME') ? WEBSITE_NAME : 'MyShop' ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="header">
    <div class="container header__container">
        <a href="index.php" class="header__logo">
            <span>🛍️</span>
            <span><?= defined('WEBSITE_NAME') ? WEBSITE_NAME : 'MaBoutique' ?></span>
        </a>
        <nav class="header__nav">
            <a href="index.php" class="header__nav-link">Home</a>
            <a href="catalogue.php" class="header__nav-link">Catalog</a>
            <a href="contact.php" class="header__nav-link">Contact</a>
        </nav>
        <div class="header__actions">
            <a href="panier.php" class="header__cart">
                🛒
                <span class="header__cart-badge"><?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?></span>
            </a>
            <a href="connexion.php" class="btn btn--primary btn--sm">Log in</a>
        </div>
        <button class="header__menu-toggle">☰</button>
    </div>
</header>

<main class="main-content">
    <div class="container">

        <nav class="breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <a href="catalogue.php">Catalog</a>
            <span>/</span>
            <?php if ($product !== null && $category !== ''): ?>
                <a href="catalogue.php?category=<?= urlencode($category) ?>"><?= $esc($category) ?></a>
                <span>/</span>
            <?php endif; ?>
            <span class="breadcrumb__current"><?= $esc($name) ?></span>
        </nav>

        <?php if ($product === null): ?>
            <div class="alert alert--error">
                ✗ Can't find product (id=<?= (int)$id ?>)
            </div>
            <a href="catalogue.php" class="btn btn--outline btn--lg">← Back to catalog</a>

        <?php else: ?>

        <div class="product-detail">
            <div class="product-detail__gallery">
                <div class="product-detail__main-image">
                    <img src="<?= $esc($image) ?>" alt="<?= $esc($name) ?>">
                </div>
            </div>

            <div class="product-detail__info">
                <div class="product-detail__badges">
                    <?php if (function_exists('isNew') && $dateAdded && isNew($dateAdded)): ?>
                        <span class="badge badge--new">New</span>
                    <?php endif; ?>

                    <?php if (function_exists('isOnSale') && isOnSale($discount)): ?>
                        <span class="badge badge--promo">-<?= (int)$discount ?>%</span>
                    <?php endif; ?>
                </div>

                <?php if ($category !== ''): ?>
                    <span class="product-detail__category"><?= $esc($category) ?></span>
                <?php endif; ?>

                <h1 class="product-detail__title"><?= $esc($name) ?></h1>

                <div class="product-detail__price">
                    <span class="product-detail__price-current">
                        <?= function_exists('formatPrice') ? formatPrice($priceToShow) : number_format($priceToShow, 2, ',', ' ') . ' €' ?>
                    </span>

                    <?php if ($oldPriceToShow !== null): ?>
                        <span class="product-detail__price-old">
                            <?= function_exists('formatPrice') ? formatPrice($oldPriceToShow) : number_format($oldPriceToShow, 2, ',', ' ') . ' €' ?>
                        </span>
                    <?php endif; ?>
                </div>

                <?php if ($description !== ''): ?>
                    <p class="product-detail__description"><?= $esc($description) ?></p>
                <?php endif; ?>

                <div class="product-detail__stock <?= $stock > 0 ? 'product-detail__stock--available' : 'product-detail__stock--unavailable' ?>">
                    <?= $stock > 0 ? "✓ In Stock ($stock available)" : "✗ Out of stock" ?>
                    <?= $stockBadgeHtml ? " " . $stockBadgeHtml : "" ?>
                </div>

                <form action="panier.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                    <input type="hidden" name="action" value="add">

                    <div class="product-detail__quantity">
                        <label>Quantité :</label>
                        <div class="quantity-selector">
                            <button type="button" class="quantity-selector__btn" onclick="decrementQty()">−</button>
                            <input
                                type="number"
                                name="quantity"
                                value="1"
                                min="1"
                                max="<?= max(1, $stock) ?>"
                                class="quantity-selector__input"
                                id="quantity"
                                <?= $stock <= 0 ? 'disabled' : '' ?>
                            >
                            <button type="button" class="quantity-selector__btn" onclick="incrementQty()">+</button>
                        </div>
                    </div>

                    <div class="product-detail__actions">
                        <button type="submit" class="btn btn--primary btn--lg" <?= $stock <= 0 ? 'disabled' : '' ?>>
                            🛒 Add to cart
                        </button>
                        <a href="catalogue.php" class="btn btn--outline btn--lg">
                            ← Keep shopping
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <?php endif; ?>

    </div>
</main>

<footer class="footer">
    <div class="container">
        <div class="footer__grid">
            <div class="footer__section">
                <h4>À propos</h4>
                <p><?= defined('WEBSITE_NAME') ? WEBSITE_NAME : 'MaBoutique' ?> - Votre destination shopping en ligne.</p>
            </div>
            <div class="footer__section">
                <h4>Navigation</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="catalogue.php">Catalog</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="footer__section">
                <h4>Mon compte</h4>
                <ul>
                    <li><a href="connexion.php">Log in</a></li>
                    <li><a href="inscription.php">Register</a></li>
                    <li><a href="panier.php">My cart</a></li>
                </ul>
            </div>
            <div class="footer__section">
                <h4>Formation</h4>
                <ul>
                    <li><a href="#">Jour 1-5 : Bases</a></li>
                    <li><a href="#">Jour 6-10 : Avancé</a></li>
                    <li><a href="#">Jour 11-14 : Pro</a></li>
                </ul>
            </div>
        </div>
        <div class="footer__bottom">
            <p>&copy; <?= date('Y') ?> <?= defined('WEBSITE_NAME') ? WEBSITE_NAME : 'MaBoutique' ?></p>
        </div>
    </div>
</footer>

<script>
function incrementQty() {
    const input = document.getElementById('quantity');
    if (!input || input.disabled) return;
    const max = parseInt(input.max || "1", 10);
    if (parseInt(input.value, 10) < max) input.value = parseInt(input.value, 10) + 1;
}
function decrementQty() {
    const input = document.getElementById('quantity');
    if (!input || input.disabled) return;
    if (parseInt(input.value, 10) > 1) input.value = parseInt(input.value, 10) - 1;
}
</script>

</body>
</html>
