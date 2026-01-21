<?php
require_once __DIR__ . "/../app/helpers.php";
session_start();
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=shop;charset=utf8mb4",
        "dev",
        "dev",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    $e->getMessage();
}


if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare(
        "SELECT * FROM products WHERE id IN ($placeholders)"
    );

    $stmt->execute($ids);
    $productsInCart = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


$action = $_POST['action'] ?? '';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($action === 'add') {
    $id  = (int)($_POST['idCart'] ?? 0);
    $quantity = (int)($_POST['quantityAdd'] ?? 1);
    var_dump($quantity);
    $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $stock = (int)$stmt->fetchColumn();

    $currentQuantity = $_SESSION['cart'][$id] ?? 0;

    if ($id > 0 && $quantity > 0 && ($currentQuantity + $quantity) <= $stock) {
        $_SESSION['cart'][$id] = $currentQuantity + $quantity;
        $_SESSION['flash'] = 'Added to cart';
        header('Location: catalog.php');
        exit;
    } else {
        header('Location: catalog.php');
        $_SESSION['flash'] = 'Not enough stock';
        exit;
    }


}

if ($action === 'update') {
    $id  = (int)($_POST['idUpdate'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 1);

    $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $stock = (int)$stmt->fetchColumn();

    if ($id > 0 && $quantity > 0) {
        $_SESSION['cart'][$id] = min($quantity, $stock);
    }

    header('Location: cart.php');
    exit;
}

if ($action === 'remove') {
    $id = (int)($_POST['idRemove'] ?? 0);

    if ($id > 0) {
        unset($_SESSION['cart'][$id]);
    }

    header('Location: cart.php');
    exit;
}

if ($action === 'emptyCart') {
    unset($_SESSION['cart']);
    header('Location: cart.php');
    exit;
}

//Old code
// if (isset($_POST["idCart"])) {
//     $id = $_POST["idCart"];
//     $quantity = ($_POST["quantityAdd"] ?? 0);
//     $currentQuantity = $_SESSION["cart"][$id] ?? 0;
//     $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
//     $stmt->execute([$id]);
//     $stock = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     if ($quantity + $currentQuantity <= $stock{
//         if (!isset($_SESSION["cart"][$id])) {
//             $_SESSION["cart"][$id] = intval($quantity);
//             echo "<script>alert ('Added to cart')</script>";
//         } else {
//             $_SESSION["cart"][$id] = $_SESSION["cart"][$id] + $quantity;
//             echo "<script>alert ('Added to cart')</script>";
//         }
//     }else {
//     echo "<script>alert ('Not enough stock')</script>";
//     }
// }



// if (isset($_POST['remove'])) {
//     $id = $_POST['idRemove'];
//     unset($_SESSION['cart'][$id]);
// }

// if (isset($_POST['emptyCart'])) {
// unset($_SESSION['cart']);
// }

// if (isset($_POST['update'])) {
//     $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
//     $stmt->execute([(int)$_POST['idUpdate']]);
//     $stock = (int)$stmt->fetchColumn();
//     $quantity = ($_POST["quantityToAdd"] ?? 0);
//     $currentQuantity = $_SESSION['cart'][$_POST['idUpdate']];
//     if ($quantity + $currentQuantity <= $stock && $quantity + $currentQuantity > 0) {
//         $id = $_POST['idUpdate'];
//         $quantity = $_POST['quantity'];
//         $_SESSION['cart'][$id] = $quantity;
//     }else{
//         echo "alert ('Not enough stock')";
//     }
// }

//var_dump($productsInCart);
//echo '<br>';
//var_dump($products);

//Actual total of products in the cart
if (!isset($_SESSION["totalItemsCart"])) {
    $_SESSION["totalItemsCart"] = 0;
}

$_SESSION["totalItemsCart"] = 0;
foreach ($_SESSION["cart"] as $key => $value) {
    $_SESSION["totalItemsCart"] += $value ;
}

//Total price of products in the cart
$_SESSION['totalCart'] = 0;
foreach ($_SESSION["cart"] as $key => $value) {
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$key]);
    $price = $stmt->fetchColumn();
    $stmt = $pdo->prepare("SELECT discount FROM products WHERE id = ?");
    $stmt->execute([$key]);
    $discount = $stmt->fetchColumn();
    $_SESSION['totalCart'] += $price * $value * (1 - $discount / 100);
}

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

<!-- ============================================
     HEADER
     JOUR 12 : Extraire dans views/layout.php
     ============================================ -->
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
                <!-- JOUR 7 :  -->
                <?= count($_SESSION['cart'] ?? []) ?>
                <span class="header__cart-badge">3</span>
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
            <!-- JOUR 7 :  articles -->
            <p class="page-subtitle"><?= count($_SESSION['cart'] ?? []) ?> items in your cart </p>
        </div>

        <!-- ============================================
             ALERTES / MESSAGES FLASH
             JOUR 7 : Afficher $_SESSION['flash']
             ============================================ -->
        <!-- Exemples de messages (décommenter pour voir) -->
        <!--
        <div class="alert alert--success">
            ✓ Panier mis à jour avec succès !
            <button class="alert__close">×</button>
        </div>
        <div class="alert alert--warning">
            ⚠ Certains articles ont un stock limité.
            <button class="alert__close">×</button>
        </div>
        <div class="alert alert--info">
            ℹ Livraison gratuite à partir de 50€ d'achat !
            <button class="alert__close">×</button>
        </div>
        -->

        <!-- ============================================
             PANIER AVEC ARTICLES
             JOUR 7 : foreach sur $_SESSION['cart']
             ============================================ -->
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
                        <!-- JOUR 7 : Récupérer les infos produit depuis la BDD -->

                        <!-- Article 1 -->
                        <?php foreach ($productsInCart as $product): ?>
                        <tr>
                            <td>
                                <div class="cart-item">
                                    <div class="cart-item__image">
                                        <img src="<?= e($product["image"])?>" alt="<?= e($product["name"])?>">
                                    </div>
                                    <div>
                                        <!-- JOUR 7 :  -->
                                        <div class="cart-item__title"><?= e($product['name']) ?></div>
                                        <div class="cart-item__category"><?= e($product['category']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?= formatPrice($product['price'] * (1 - $product['discount'] / 100)) ?>
                            </td>
                            <td>
                                <!-- JOUR 7 : Formulaire pour modifier la quantité -->
                                <form action="cart.php" method="POST" style="display:inline">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="idUpdate" value="<?= $product['id'] ?>">

                                    <div class="quantity-selector">
                                        <button type="button" onclick="this.nextElementSibling.stepDown()">−</button>

                                        <input
                                            type="number"
                                            name="quantity"
                                            value="<?= $_SESSION['cart'][$product['id']] ?>"
                                            min="1"
                                            style="width:50px"
                                        >

                                        <button type="button" onclick="this.previousElementSibling.stepUp()">+</button>
                                    </div>

                                    <button type="submit">Update</button>
                                </form>
                            </td>
                            <td>    
                                <span class="cart-item__total"><?= formatPrice($product['price'] * $_SESSION['cart'][$product['id']] * (1 - $product['discount'] / 100)) ?></span>
                            </td>
                            <td>
                                <!-- JOUR 7 : Formulaire pour supprimer -->
                                <form action="cart.php" method="POST" style="display:inline">
                                    <input type="hidden" name="idRemove" value="<?= $product['id']?>">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="cart-item__remove" title="Remove">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="cart-actions">
                    <a href="catalog.php" class="btn btn--outline">
                        ← Continue my shopping
                    </a>
                    <!-- JOUR 7 : Formulaire pour vider le panier -->
                    <form action="cart.php" method="POST" style="display:inline">
                        <input type="hidden" name="action" value="emptyCart">
                        <button type="submit" class="btn btn--danger">
                            🗑️ Empty the cart
                        </button>
                    </form>
                </div>
            </div>

            <!-- ============================================
                 RÉCAPITULATIF
                 JOUR 5 : Calculs avec helpers
                 ============================================ -->
            <aside class="cart-summary">
                <h3 class="cart-summary__title">Summing up</h3>

                <div class="cart-summary__row">
                    <span>Sub-total</span>
                    <span><?= formatPrice($_SESSION['totalCart']) ?></span>
                </div>
                <div class="cart-summary__row">
                    <span>VAT (20%)</span>
                    <span><?= formatPrice($_SESSION['totalCart'] * 0.2) ?></span>
                </div>
                <div class="cart-summary__row">
                    <span>Delivery</span>
                    <!-- JOUR 4 : Gratuit si > 50€ -->
                    <?php if ($_SESSION['totalCart'] > 50): ?>
                        <?php $freeDelivery = true; ?>
                         <span style="color: #22c55e;">Free</span>
                    <?php else: ?>
                        <?php $freeDelivery = false; ?>
                        <span style="color: #C52289;">5,99$</span>
                    <?php endif; ?>
                </div>
                <div class="cart-summary__row cart-summary__row--total">
                    <span>Total ATI</span>
                    <?php if ($freeDelivery): ?>
                        <span><?= formatPrice($_SESSION['totalCart'] + $_SESSION['totalCart'] * 0.2) ?></span>
                    <?php else: ?>
                        <span><?= formatPrice($_SESSION['totalCart'] + $_SESSION['totalCart'] * 0.2 + 5.99) ?></span>
                    <?php endif; ?>
                </div>

                <div class="cart-summary__checkout">
                    <!-- JOUR 7 : Vérifier si user connecté -->
                    <?php if (isset($_SESSION['auth'])): ?>
                        <a href="checkout.php" class="btn btn--primary btn--block btn--lg">
                            Proceed to payment
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn--primary btn--block btn--lg">
                            You need to be logged in to proceed
                        </a>
                    <?php endif; ?>
                    </p>
                </div>
            </aside>
        </div>
        <?php endif; ?> 
        <!-- ============================================
             PANIER VIDE (template alternatif)
             JOUR 7 : 
             ============================================ -->
        <?php if (empty($_SESSION['cart'])): ?>
            <div class="cart-empty">
                <div class="cart-empty__icon">🛒</div>
                <h2>Your cart is empty</h2>
                <p>Discover our products and add them to your cart</p>
                <a href="catalog.php" class="btn btn--primary btn--lg mt-md">
                    Check catalog
                </a>
            </div>
        <?php endif; ?> 

    </div>
</main>

<!-- ============================================
     FOOTER
     JOUR 12 : Extraire dans views/layout.php
     ============================================ -->
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
