<?php
// starter-project/public/catalog.php
//require_once __DIR__ . '/../app/data.php';
require_once __DIR__ . '/../app/helpers.php';

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

session_start();
$stmt = $pdo->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$inStock = 0;
$onSale = 0;
$outOfStock = 0;

foreach ($products as $product) {
    $product['stock'] > 0 ? $inStock++ : $outOfStock++;
    if ($product['discount'] > 0) {
        $onSale++;
    }
}

$categoryCounts = [];

foreach ($products as $product) {
    $category = $product['category'];
    $categoryCounts[$category] = ($categoryCounts[$category] ?? 0) + 1;
}

$categoriesSide = array_values(array_unique(array_map(fn ($p) => $p['category'], $products)));
$selectedCategories = $_GET['categories'] ?? [];
$nameSearch = $_GET['nameSearch'] ?? '';
$maxPrice = $_GET['price_max'] ?? '';
$minPrice = $_GET['price_min'] ?? '';
$inStockOnly = isset($_GET['in_stock']);
$categoriesSelected = $_GET['categories'] ?? [];
$countTotal = 0;

if (!isset($_SESSION["totalCart"])) {
    $_SESSION["totalCart"] = 0;
}

if (isset($_SESSION['flash'])) {
    echo '<script>alert ("'.$_SESSION['flash'].'")</script>';
    unset($_SESSION['flash']);
}

if (isset($_POST["idCart"])) {
    $id = $_POST["idCart"];
    $quantity = ($_POST["quantityAdd"] ?? 0);
    $currentQuantity = $_SESSION["cart"][$id] ?? 0;
    if ($quantity + $currentQuantity <= $products[$_POST["idCart"]]["stock"]) {
        if (!isset($_SESSION["cart"][$id])) {
            $_SESSION["cart"][$id] = intval($quantity);
            echo "<script>alert ('Added to cart')</script>";
        } else {
            $_SESSION["cart"][$id] = $_SESSION["cart"][$id] + $quantity;
            echo "<script>alert ('Added to cart')</script>";
        }
    } else {
        echo "<script>alert ('Not enough stock')</script>";
    }
}

if (!isset($_SESSION["totalItemsCart"])) {
    $_SESSION["totalItemsCart"] = 0;
}
if (isset($_SESSION["cart"])) {
    $_SESSION["totalItemsCart"] = 0;
    foreach ($_SESSION["cart"] as $key => $value) {
        $_SESSION["totalItemsCart"] += $value ;
    }
} else {
    $_SESSION["totalItemsCart"] = 0;
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog - MyShop</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="header">
    <div class="container header__container">
        <a href="index.html" class="header__logo">🛍️ MyShop</a>
        <nav class="header__nav">
            <a href="index.html" class="header__nav-link">Home</a>
            <a href="catalog.html" class="header__nav-link header__nav-link--active">Catalog</a>
            <a href="contact.html" class="header__nav-link">Contact</a>
        </nav>
        <div class="header__actions">
            <a href="cart.php" class="header__cart">🛒<span class="header__cart-badge">3</span></a>
            <a href="connexion.html" class="btn btn--primary btn--sm">Log in</a>
        </div>
        <button class="header__menu-toggle">☰</button>
    </div>
</header>

<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Our Catalog</h1>
            <p class="page-subtitle">Discover all out products</p>
            <p><strong><?= $inStock ?></strong> in stock · <strong><?= $outOfStock ?></strong> out of stock · <strong><?= $onSale ?></strong> on sale</p>
        </div>

        <div class="catalog-layout">

            <!-- ============================================
                 SIDEBAR FILTRES
                 JOUR 6 : Formulaire GET + conservation valeurs
                 ============================================ -->
            <aside class="catalog-sidebar">
                <form method="GET" action="catalog.php">
                    <div class="catalog-sidebar__section">
                        <h3 class="catalog-sidebar__title">Search</h3>
                        <!-- JOUR 6 : value="<?= e($_GET['nameSearch'] ?? '') ?>" -->
                        <input type="text" name="nameSearch" class="form-input" placeholder="Search..." value="<?= e($nameSearch) ?>">
                    </div>

                    <div class="catalog-sidebar__section">
                        <h3 class="catalog-sidebar__title">Categories</h3>
                        <div class="catalog-sidebar__categories">
                            <!-- JOUR 6 : checked si in_array(...) -->
                            <?php foreach ($categoriesSide as $product): ?>
                            <label class="form-checkbox">
                                <input type="checkbox" name="categories[]" value="<?= e($product) ?>" <?= in_array($product, $selectedCategories, true) ? 'checked' : '' ?>>
                                <span><?= e($product). " (".$categoryCounts[$product].")"?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="catalog-sidebar__section">
                        <h3 class="catalog-sidebar__title">Price</h3>
                        <div class="catalog-sidebar__price-inputs">
                            <div class="form-group">
                                <label class="form-label">Min</label>
                                <input type="number" name="price_min" class="form-input" placeholder="0$" min="0" value="<?= e($minPrice) ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Max</label>
                                <input type="number" name="price_max" class="form-input" placeholder="100$" min="0" value="<?= e($maxPrice) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="catalog-sidebar__section">
                        <h3 class="catalog-sidebar__title">Availability</h3>
                        <label class="form-checkbox">
                            <input type="checkbox" name="in_stock" value="1" <?php if (isset($_GET['in_stock'])) {
                                echo "checked='checked'";
                            } ?>>
                            <span>Only in stock</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn--primary btn--block">Apply</button>
                    <a href="catalog.php" class="btn btn--secondary btn--block mt-sm">Reset</a>
                </form>
            </aside>

            <div class="catalog-main">
                <div class="catalog-header">
                    <p><strong><?= $countTotal ?></strong> products found</p>
                    <div class="catalog-header__sort">
                        <label>Sort:</label>
                        <select class="form-select" style="width:auto">
                            <option>Name A-Z</option>
                            <option>Name Z-A</option>
                            <option>Price ↑</option>
                            <option>Price ↓</option>
                        </select>
                    </div>
                </div>

                <!-- ============================================
                     8 PRODUITS
                     JOUR 3 : foreach
                     JOUR 4 : Badges conditionnels
                     ============================================ -->
                <div class="products-grid">
<?php foreach ($products as $product): ?>
    <?php
        if ($nameSearch !== '' && stripos($product['name'], $nameSearch) === false) {
            continue;
        }
    if ($maxPrice !== '' && $product['price'] > (float)$maxPrice) {
        continue;
    }
    if ($minPrice !== '' && $product['price'] < (float)$minPrice) {
        continue;
    }
    if ($inStockOnly && $product['stock'] <= 0) {
        continue;
    }
    if (!empty($categoriesSelected) && !in_array($product['category'], $categoriesSelected)) {
        continue;
    }

    $id = $product['id'] ?? '';
    $isSale = isOnSale($product['discount']);
    $isNewProduct = isNew($product['dateAdded']);
    $stock = (int)($product['stock'] ?? 0);
    $name = $product['name'] ?? '';
    $image = $product['image'] ?? '';
    $price = $product['price'] ?? '';
    $discount = $product['discount'] ?? '';
    $description = $product['description'] ?? '';
    $category = $product['category'] ?? '';
    $countTotal++;
    ?>
    <article class="product-card">
        <div class="product-card__image-wrapper">
            <img src="<?= e($image) ?>" alt="<?= e($name) ?>" class="product-card__image">
            <div class="product-card__badges">
                <?php if ($isNewProduct): ?>
                    <span class="badge badge--new">New</span>
                <?php endif; ?>

                <?php if ($isSale): ?>
                    <span class="badge badge--promo">-<?= (int)$isSale ?>%</span>
                <?php endif; ?>

                <?php if ($stock > 0 && $stock <= 3): ?>
                    <span class="badge badge--low-stock">lasts</span>
                <?php endif; ?>

                <?php if ($stock <= 0): ?>
                    <span class="badge badge--out-of-stock">Out of stock</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-card__content">
            <span class="product-card__category"><?= e($category) ?></span>
            <a href="product.php<?= $id !== '' ? ('?id=' . urlencode((string)$id)) : '' ?>" class="product-card__title">
                <?= e($name) ?>
            </a>

            <div class="product-card__price">
                <?php if ($isSale): ?>
                    <span class="product-card__price-current"><?= formatPrice(calculateDiscounted($price, $isSale)) ?></span>
                    <span class="product-card__price-old"><?= formatPrice($price) ?></span>
                <?php else: ?>
                    <span class="product-card__price-current"><?= formatPrice($price) ?></span>
                <?php endif; ?>
            </div>

            <?php if ($stock <= 0): ?>
                <p class="product-card__stock product-card__stock--out">✗ Out of stock</p>
            <?php elseif ($stock <= 3): ?>
                <p class="product-card__stock product-card__stock--low">⚠ Only <?= $stock ?> left</p>
            <?php else: ?>
                <p class="product-card__stock product-card__stock--available">✓ In stock (<?= $stock ?>)</p>
            <?php endif; ?>

            <div class="product-card__actions">
                <?php if ($stock > 0): ?>
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="idCart" value="<?= $id ?>">

                        <button type="button" onclick="this.nextElementSibling.stepDown()">−</button>

                        <input
                            type="number"
                            name="quantityAdd"
                            value="1"
                            min="1"
                            step="1"
                        >

                        <button type="button" onclick="this.previousElementSibling.stepUp()">+</button>

                        <button type="submit">Add</button>
                    </form>
                <?php else: ?>
                    <button class="btn btn--secondary btn--block" disabled>Unavailable</button>
                <?php endif; ?>
            </div>
        </div>
    </article>
<?php endforeach; ?>
</div>

                <!-- ============================================
                     PAGINATION
                     JOUR 6 : Générer dynamiquement
                     ============================================ -->
                <nav class="pagination">
                    <a class="pagination__item pagination__item--disabled">←</a>
                    <a class="pagination__item pagination__item--active">1</a>
                    <a class="pagination__item">2</a>
                    <a class="pagination__item">3</a>
                    <a class="pagination__item">→</a>
                </nav>
            </div>
        </div>
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
