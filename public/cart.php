<?php
// public/cart.php
declare(strict_types=1);

require_once __DIR__ . '/../app/helpers.php';
$files = glob(__DIR__ . '/../app/Entity/*.php');

foreach ($files as $file) {
    require_once($file);   
}

// Repositories
$repoFiles = glob(__DIR__ . '/../app/Repository/*.php');
foreach ($repoFiles as $file) {
    require_once($file);
}

require_once __DIR__ .'/../config/Database.php';


session_start();

function getCart(): Cart {
    if (!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof Cart)) {
        $_SESSION['cart'] = new Cart();
    }
    return $_SESSION['cart'];
}
$cart = getCart();

$pdo = Database::getInstance();

$categoryRepo = new CategoryRepository($pdo);
$productRepo  = new ProductRepository($pdo, $categoryRepo);


$action = $_POST['action'] ?? '';

if ($action === 'add') {
    $id = (int)($_POST['idCart'] ?? 0);
    $qty = max(1, (int)($_POST['quantityAdd'] ?? 1));

    $product = $id > 0 ? $productRepo->find($id) : null;
    if (!$product) {
        $_SESSION['flash'] = 'Product not found';
        header('Location: catalog.php'); exit;
    }
    if ($cart->getCartItem($id) !== null) {
        $current = $cart->getCartItem(id: $id)->getQuantity();
        if ($product->canAddToCart($qty, $current)) {
            $cart->getCartItem($id)->setQuantity($current + $qty);
            $_SESSION['flash'] = 'Added to cart';}
        else {
        $_SESSION['flash'] = 'Not enough stock';}
    } 
    else {
        if ($product->canAddToCart($qty, 0)) {
            $cart->addProduct($product, $qty);}
        else {
        $_SESSION['flash'] = 'Not enough stock';}
    }
    header('Location: catalog.php');
    exit;
}

if ($action === 'update') {
    $id = (int)($_POST['idUpdate'] ?? 0);
    $qty = (int)($_POST['quantity'] ?? 1);
    $cart = getCart();
    if ($id <= 0) { header('Location: cart.php'); exit; }

    $product = $productRepo->find($id);
    if (!$product) {
        $cart->removeProduct($id);
        $_SESSION['flash'] = "Product removed (Doesn't exist)";
        header('Location: cart.php'); exit;
    }

    if ($qty < 1) {
        $cart->removeProduct($id);
        $_SESSION['flash'] = 'Item removed';
        header('Location: cart.php'); exit;
    }

    $cart->getCartItem($id)->setQuantity(min($qty, $product->getStock()));
    $_SESSION['flash'] = 'Cart updated';
    header('Location: cart.php');
    exit;
}

if ($action === 'remove') {
    $id = (int)($_POST['idRemove'] ?? 0);
    if ($id > 0) getCart()->removeProduct($id);
    $_SESSION['flash'] = 'Item removed';
    header('Location: cart.php');
    exit;
}

if ($action === 'emptyCart') {
    getCart()->clear();
    $_SESSION['flash'] = 'Cart emptied';
    header('Location: cart.php');
    exit;
}

// Build cart view data (Products in cart)

$cart = getCart();

// Refresh products in cart from DB (price/stock/discount changes),
// and remove items that no longer exist.
foreach (array_keys($cart->getItems()) as $id) {
    $product = $productRepo->find((int)$id);
    if ($product === null) {
        $cart->removeProduct((int)$id);
        continue;
    }
    $cart->setProduct($product);
}

$totalCart = $cart->getTotal();

$freeDelivery = $totalCart > 50;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My cart - MyShop</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="header">
    <div class="container header__container">
        <a href="index.html" class="header__logo">
            <span>🛍️</span>
            <span>MyShop</span>
        </a>
        <nav class="header__nav">
            <a href="index.php" class="header__nav-link">Home</a>
            <a href="catalog.php" class="header__nav-link">Catalog</a>
            <a href="contact.php" class="header__nav-link">Contact</a>
        </nav>
        <div class="header__actions">
            <a href="cart.php" class="header__cart">
                🛒
                <span class="header__cart-badge"><?= (int)(getCart()->countUnique()) ?></span>
            </a>
            <a href="login.php" class="btn btn--primary btn--sm">Log in</a>
        </div>
        <button class="header__menu-toggle">☰</button>
    </div>
</header>

<main class="main-content">
    <div class="container">

        <div class="page-header">
            <h1 class="page-title">My cart</h1>
            <p class="page-subtitle"><?= (int)(getCart()->countAllItems()) ?> items in your cart</p>
        </div>

        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert--info">
                <?= e((string)$_SESSION['flash']) ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

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
                                    <form action="cart.php" method="POST" style="display:inline">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="idUpdate" value="<?= (int)$product->getId() ?>">

                                        <div class="quantity-selector">
                                            <button type="button" onclick="this.nextElementSibling.stepDown()">−</button>
                                            <input type="number" name="quantity" value="<?= $qty ?>" min="1" max="<?= (int)$product->getStock() ?>" style="width:50px">
                                            <button type="button" onclick="this.previousElementSibling.stepUp()">+</button>
                                        </div>

                                        <button type="submit">Update</button>
                                    </form>
                                </td>
                                <td>
                                    <span class="cart-item__total"><?= formatPrice($item->getTotal()) ?></span>
                                </td>
                                <td>
                                    <form action="cart.php" method="POST" style="display:inline" onsubmit="return confirm('Remove this item?');">
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
                    <a href="catalog.php" class="btn btn--outline">← Continue my shopping</a>
                    <form action="cart.php" method="POST" style="display:inline" onsubmit="return confirm('Empty the cart?');">
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
                        <a href="checkout.php" class="btn btn--primary btn--block btn--lg">Proceed to payment</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn--primary btn--block btn--lg">You need to be logged in to proceed</a>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
        <?php else: ?>
            <div class="cart-empty">
                <div class="cart-empty__icon">🛒</div>
                <h2>Your cart is empty</h2>
                <p>Discover our products and add them to your cart</p>
                <a href="catalog.php" class="btn btn--primary btn--lg mt-md">Check catalog</a>
            </div>
        <?php endif; ?>

    </div>
</main>

<footer class="footer">
    <div class="container">
        <div class="footer__grid">
            <div class="footer__section">
                <h4>About</h4>
                <p>MyShop - Your online shopping destination.</p>
            </div>
            <div class="footer__section">
                <h4>Navigation</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="catalog.php">Catalog</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="footer__section">
                <h4>My account</h4>
                <ul>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="cart.php">My Cart</a></li>
                </ul>
            </div>
            <div class="footer__section">
                <h4>Formation</h4>
                <ul>
                    <li><a href="#">Jour 1-5 : Bases</a></li>
                    <li><a href="#">Jour 6-10 : Avancé</a></li>
                    <li><a href="#">Jour 11-14 : Pro</a></li>
                    <li><a href="#">Jour 11-14 : Pro</a></li>
                </ul>
            </div>
        </div>
        <div class="footer__bottom">
            <p>&copy; <?= date('Y') ?> MyShop - Formation PHP 14 jours</p>
        </div>
    </div>
</footer>

</body>
</html>
