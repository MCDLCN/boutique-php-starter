<?php
session_start();
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=boutique;charset=utf8mb4",
        "dev",
        "dev",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    $e->getMessage();
}



if (isset($_POST['idRemove'])) {
    $id = $_POST['idRemove'];       
    unset($_SESSION['cart'][$id]);
}

if (isset($_POST['emptyCart'])) {
unset($_SESSION['cart']);   
}

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $products= $_SESSION["cart"];
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare(
    "SELECT id, name, price, stock FROM products WHERE id IN ($placeholders)"
);

$stmt->execute($ids);
$productsInCart = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (isset($_POST['idUpdate'])) {
    $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
    $stmt->execute([(int)$_POST['idUpdate']]);
    $stock = (int)$stmt->fetchColumn();
    $quantity = ($_POST["quantity"] ?? 0);
    $currentQuantity = $_SESSION['cart'][$_POST['idUpdate']];
    if ($quantity + $currentQuantity <= $stock){
        $id = $_POST['idUpdate'];   
        $quantity = $_POST['quantity'];
        $_SESSION['cart'][$id] = $quantity;
    }else{
        echo "alert ('Not enough stock')";
    }
}

//var_dump($productsInCart);
echo '<br>';
//var_dump($products);
if (!isset($_SESSION["totalItemsCart"])) {
    $_SESSION["totalItemsCart"] = 0;
}

$_SESSION["totalItemsCart"] = 0;
foreach ($_SESSION["cart"] as $key => $value) {
         $_SESSION["totalItemsCart"] += $value ;}


$_SESSION['totalCart'] = 0;
foreach ($_SESSION["cart"] as $key => $value) {
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$key]);
    $price = $stmt->fetchColumn();
    $_SESSION['totalCart'] += $price * $value;
}
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier - MaBoutique</title>
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
            <span>MaBoutique</span>
        </a>
        <nav class="header__nav">
            <a href="index.html" class="header__nav-link">Accueil</a>
            <a href="catalogue.html" class="header__nav-link">Catalogue</a>
            <a href="contact.html" class="header__nav-link">Contact</a>
        </nav>
        <div class="header__actions">
            <a href="panier.html" class="header__cart">
                🛒
                <!-- JOUR 7 : <?= count($_SESSION['cart'] ?? []) ?> -->
                <span class="header__cart-badge">3</span>
            </a>
            <a href="connexion.html" class="btn btn--primary btn--sm">Connexion</a>
        </div>
        <button class="header__menu-toggle">☰</button>
    </div>
</header>

<main class="main-content">
    <div class="container">

        <div class="page-header">
            <h1 class="page-title">Mon Panier</h1>
            <!-- JOUR 7 : <?= count($_SESSION['cart']) ?> articles -->
            <p class="page-subtitle">3 articles dans votre panier</p>
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
        <div class="cart-layout">
            <div class="cart-table">
                <table>
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- JOUR 7 : <?php foreach ($_SESSION['cart'] as $productId => $item): ?> -->
                        <!-- JOUR 7 : Récupérer les infos produit depuis la BDD -->

                        <!-- Article 1 -->
                        <tr>
                            <td>
                                <div class="cart-item">
                                    <div class="cart-item__image">
                                        <img src="https://via.placeholder.com/100x100/e2e8f0/64748b?text=T-shirt" alt="T-shirt">
                                    </div>
                                    <div>
                                        <!-- JOUR 7 : <?= htmlspecialchars($product['name']) ?> -->
                                        <div class="cart-item__title">T-shirt Premium Bio</div>
                                        <!-- JOUR 7 : <?= htmlspecialchars($product['category']) ?> -->
                                        <div class="cart-item__category">Vêtements</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <!-- JOUR 5 : <?= formatPrice($product['price']) ?> -->
                                35,99 €
                            </td>
                            <td>
                                <!-- JOUR 7 : Formulaire pour modifier la quantité -->
                                <form action="panier.html" method="POST" style="display:inline">
                                    <input type="hidden" name="product_id" value="1">
                                    <input type="hidden" name="action" value="update">
                                    <div class="quantity-selector">
                                        <button type="submit" name="quantity" value="1" class="quantity-selector__btn">−</button>
                                        <!-- JOUR 7 : value="<?= $item['quantity'] ?>" -->
                                        <input type="number" name="quantity" value="2" min="1" class="quantity-selector__input" style="width:50px">
                                        <button type="submit" name="quantity" value="3" class="quantity-selector__btn">+</button>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <!-- JOUR 5 : <?= formatPrice($product['price'] * $item['quantity']) ?> -->
                                <span class="cart-item__total">71,98 €</span>
                            </td>
                            <td>
                                <!-- JOUR 7 : Formulaire pour supprimer -->
                                <form action="panier.html" method="POST" style="display:inline">
                                    <input type="hidden" name="product_id" value="1">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="cart-item__remove" title="Supprimer">🗑️</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Article 2 -->
                        <tr>
                            <td>
                                <div class="cart-item">
                                    <div class="cart-item__image">
                                        <img src="https://via.placeholder.com/100x100/e2e8f0/64748b?text=Sneakers" alt="Sneakers">
                                    </div>
                                    <div>
                                        <div class="cart-item__title">Sneakers Urban</div>
                                        <div class="cart-item__category">Chaussures</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <!-- Prix avec promo -->
                                <span style="text-decoration: line-through; color: #94a3b8;">99,99 €</span>
                                <br>79,99 €
                            </td>
                            <td>
                                <form action="panier.html" method="POST" style="display:inline">
                                    <input type="hidden" name="product_id" value="2">
                                    <input type="hidden" name="action" value="update">
                                    <div class="quantity-selector">
                                        <button type="submit" name="quantity" value="0" class="quantity-selector__btn">−</button>
                                        <input type="number" name="quantity" value="1" min="1" class="quantity-selector__input" style="width:50px">
                                        <button type="submit" name="quantity" value="2" class="quantity-selector__btn">+</button>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <span class="cart-item__total">79,99 €</span>
                            </td>
                            <td>
                                <form action="panier.html" method="POST" style="display:inline">
                                    <input type="hidden" name="product_id" value="2">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="cart-item__remove" title="Supprimer">🗑️</button>
                                </form>
                            </td>
                        </tr>

                        <!-- JOUR 7 : <?php endforeach; ?> -->
                    </tbody>
                </table>

                <div class="cart-actions">
                    <a href="catalogue.html" class="btn btn--outline">
                        ← Continuer mes achats
                    </a>
                    <!-- JOUR 7 : Formulaire pour vider le panier -->
                    <form action="panier.html" method="POST" style="display:inline">
                        <input type="hidden" name="action" value="clear">
                        <button type="submit" class="btn btn--danger">
                            🗑️ Vider le panier
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
                    <span>Sous-total</span>
                    <!-- JOUR 5 : <?= formatPrice($subtotal) ?> -->
                    <span>151,97 €</span>
                </div>
                <div class="cart-summary__row">
                    <!-- JOUR 5 : <?= formatPrice($subtotal * 0.2) ?> -->
                    <span>TVA (20%)</span>
                    <span>30,39 €</span>
                </div>
                <div class="cart-summary__row">
                    <span>Livraison</span>
                    <!-- JOUR 4 : Gratuit si > 50€ -->
                    <span style="color: #22c55e;">Gratuit</span>
                </div>
                <div class="cart-summary__row cart-summary__row--total">
                    <span>Total TTC</span>
                    <!-- JOUR 5 : <?= formatPrice($total) ?> -->
                    <span>151,97 €</span>
                </div>

                <div class="cart-summary__checkout">
                    <!-- JOUR 7 : Vérifier si user connecté -->
                    <a href="connexion.html" class="btn btn--primary btn--block btn--lg">
                        Procéder au paiement
                    </a>
                    <p class="form-hint text-center mt-sm">
                        <!-- JOUR 7 : Si non connecté -->
                        Vous devez être connecté pour commander
                    </p>
                </div>
            </aside>
        </div>

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
            <!-- JOUR 1 : Remplacer 2024 par <?= date('Y') ?> -->
            <p>&copy; <?= date('Y') ?> MyShop - Formation PHP 14 jours</p>
        </div>
    </div>
</footer>

</body>
</html>
