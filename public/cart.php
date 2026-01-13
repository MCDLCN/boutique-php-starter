<?php
// public/cart.php
declare(strict_types=1);

require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/Entity/Product.php';

session_start();

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=shop;charset=utf8mb4",
        "dev",
        "dev",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("DB error");
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? '';

function fetchProduct(PDO $pdo, int $id): ?Product {
    $stmt = $pdo->prepare("SELECT id, name, description, price, stock, category, discount, image, dateAdded FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$r) return null;

    return new Product(
        (int)$r['id'],
        (string)$r['name'],
        (string)($r['description'] ?? ''),
        (float)$r['price'],
        (int)$r['stock'],
        (string)($r['category'] ?? ''),
        (int)($r['discount'] ?? 0),
        (string)($r['image'] ?? ''),
        (string)($r['dateAdded'] ?? '')
    );
}

if ($action === 'add') {
    $id = (int)($_POST['idCart'] ?? 0);
    $qty = max(1, (int)($_POST['quantityAdd'] ?? 1));

    $product = $id > 0 ? fetchProduct($pdo, $id) : null;
    if (!$product) {
        $_SESSION['flash'] = 'Product not found';
        header('Location: catalog.php'); exit;
    }

    $current = (int)($_SESSION['cart'][$id] ?? 0);

    if ($product->canAddToCart($qty, $current)) {
        $_SESSION['cart'][$id] = $current + $qty;
        $_SESSION['flash'] = 'Added to cart';
    } else {
        $_SESSION['flash'] = 'Not enough stock';
    }

    header('Location: catalog.php');
    exit;
}

if ($action === 'update') {
    $id = (int)($_POST['idUpdate'] ?? 0);
    $qty = (int)($_POST['quantity'] ?? 1);

    if ($id <= 0) { header('Location: cart.php'); exit; }

    $product = fetchProduct($pdo, $id);
    if (!$product) {
        unset($_SESSION['cart'][$id]);
        $_SESSION['flash'] = 'Product removed (not found)';
        header('Location: cart.php'); exit;
    }

    if ($qty < 1) {
        unset($_SESSION['cart'][$id]);
        $_SESSION['flash'] = 'Item removed';
        header('Location: cart.php'); exit;
    }

    $_SESSION['cart'][$id] = min($qty, $product->stock);
    $_SESSION['flash'] = 'Cart updated';
    header('Location: cart.php');
    exit;
}

if ($action === 'remove') {
    $id = (int)($_POST['idRemove'] ?? 0);
    if ($id > 0) unset($_SESSION['cart'][$id]);
    $_SESSION['flash'] = 'Item removed';
    header('Location: cart.php');
    exit;
}

if ($action === 'emptyCart') {
    $_SESSION['cart'] = [];
    $_SESSION['flash'] = 'Cart emptied';
    header('Location: cart.php');
    exit;
}

// Build cart view data (Products in cart)
$productsInCart = [];
if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT id, name, description, price, stock, category, discount, image, dateAdded FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $byId = [];
    foreach ($rows as $r) $byId[(int)$r['id']] = $r;

    foreach ($ids as $id) {
        if (!isset($byId[(int)$id])) {
            unset($_SESSION['cart'][(int)$id]);
            continue;
        }
        $r = $byId[(int)$id];
        $productsInCart[] = new Product(
            (int)$r['id'],
            (string)$r['name'],
            (string)($r['description'] ?? ''),
            (float)$r['price'],
            (int)$r['stock'],
            (string)($r['category'] ?? ''),
            (int)($r['discount'] ?? 0),
            (string)($r['image'] ?? ''),
            (string)($r['dateAdded'] ?? '')
        );
    }
}

// Totals
$_SESSION['totalItemsCart'] = 0;
foreach ($_SESSION['cart'] as $q) $_SESSION['totalItemsCart'] += (int)$q;

$totalCart = 0.0;
foreach ($productsInCart as $p) {
    $qty = (int)($_SESSION['cart'][$p->getId()] ?? 0);
    $totalCart += $p->getFinalPrice() * $qty;
}
$_SESSION['totalCart'] = $totalCart;

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
                <span class="header__cart-badge"><?= (int)($_SESSION['totalItemsCart'] ?? 0) ?></span>
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
            <p class="page-subtitle"><?= (int)($_SESSION['totalItemsCart'] ?? 0) ?> items in your cart</p>
        </div>

        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert--info">
                <?= e((string)$_SESSION['flash']) ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['cart'])): ?>
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
                        <?php foreach ($productsInCart as $p): ?>
                            <?php $qty = (int)($_SESSION['cart'][$p->getId()] ?? 0); ?>
                            <tr>
                                <td>
                                    <div class="cart-item">
                                        <div class="cart-item__image">
                                            <img src="<?= e($p->getImage()) ?>" alt="<?= e($p->name) ?>">
                                        </div>
                                        <div>
                                            <div class="cart-item__title"><?= e($p->name) ?></div>
                                            <div class="cart-item__category"><?= e($p->category) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?= formatPrice($p->getFinalPrice()) ?>
                                </td>
                                <td>
                                    <form action="cart.php" method="POST" style="display:inline">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="idUpdate" value="<?= (int)$p->getId() ?>">

                                        <div class="quantity-selector">
                                            <button type="button" onclick="this.nextElementSibling.stepDown()">−</button>
                                            <input type="number" name="quantity" value="<?= $qty ?>" min="1" max="<?= (int)$p->stock ?>" style="width:50px">
                                            <button type="button" onclick="this.previousElementSibling.stepUp()">+</button>
                                        </div>

                                        <button type="submit">Update</button>
                                    </form>
                                </td>
                                <td>
                                    <span class="cart-item__total"><?= formatPrice($p->getFinalPrice() * $qty) ?></span>
                                </td>
                                <td>
                                    <form action="cart.php" method="POST" style="display:inline" onsubmit="return confirm('Remove this item?');">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="idRemove" value="<?= (int)$p->getId() ?>">
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
