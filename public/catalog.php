<?php
// public/catalog.php
declare(strict_types=1);

require_once __DIR__ . '/../app/helpers.php';
$files = glob(__DIR__ . '/../app/entities/*.php');

foreach ($files as $file) {
    require_once($file);   
}

session_start();
function getCart(): Cart {
    if (!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof Cart)) {
        $_SESSION['cart'] = new Cart();
    }
    return $_SESSION['cart'];
}
$cart = getCart();

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


// Flash message (from cart actions)
if (isset($_SESSION['flash'])) {
    echo '<script>alert("' . e((string)$_SESSION['flash']) . '")</script>';
    unset($_SESSION['flash']);
}

// Fetch products and hydrate objects
$stmt = $pdo->prepare("SELECT id, name, description, price, stock, category, discount, image, dateAdded FROM products");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

/** @var Product[] $products */

$categoryPool = [];
$products = [];
foreach ($rows as $r) {
    $catName = (string)($r['category'] ?? ''); 
    if (!isset($categoryPool[$catName])) {
        $categoryPool[$catName] = new Category($catName);
    }

    $category = $categoryPool[$catName];
    $category->increaseCount();

    $products[] = new Product(
        (int)$r['id'],
        (string)$r['name'],
        (string)($r['description'] ?? ''),
        (float)$r['price'],
        (int)$r['stock'],
        $category,
        (int)($r['discount'] ?? 0),
        (string)($r['image'] ?? ''),
        (string)($r['dateAdded'] ?? '')
    );
}


// Counters + category counts
$inStock = 0;
$onSale = 0;
$outOfStock = 0;

foreach ($products as $p) {
    $p->isInStock() ? $inStock++ : $outOfStock++;
    if ($p->isOnSale()) $onSale++;
}

//$categoriesSide = array_values(array_unique(array_map(fn(Product $p) => $p->getCategory()-, $products)));

// Filters (GET)
$selectedCategories = $_GET['categories'] ?? [];
$nameSearch = (string)($_GET['nameSearch'] ?? '');
$maxPrice = (string)($_GET['price_max'] ?? '');
$minPrice = (string)($_GET['price_min'] ?? '');
$inStockOnly = isset($_GET['in_stock']);



$filtered = [];

foreach ($products as $p) {
    if ($nameSearch !== '' && stripos($p->getName(), $nameSearch) === false) continue;
    if ($inStockOnly && !$p->isInStock()) continue;
    if (!empty($selectedCategories) && !in_array($p->getCategory()->getName(), $selectedCategories, true)) continue;
    if ($maxPrice !== '' && $p->getFinalPrice() > (float)$maxPrice) continue;
    if ($minPrice !== '' && $p->getFinalPrice() < (float)$minPrice) continue;

    $filtered[] = $p;
}

$sort = $_GET['sort'] ?? '';

usort($filtered, function (Product $a, Product $b) use ($sort) {
    return match ($sort) {
        'az'         => strcasecmp($a->getName(), $b->getName()),
        'za'         => strcasecmp($b->getName(), $a->getName()),
        'price_asc'  => $a->getFinalPrice() <=> $b->getFinalPrice(),
        'price_desc' => $b->getFinalPrice() <=> $a->getFinalPrice(),
        default      => 0,
    };
});
                        
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
            <a href="catalog.php" class="header__nav-link header__nav-link--active">Catalog</a>
            <a href="contact.html" class="header__nav-link">Contact</a>
        </nav>
        <div class="header__actions">
            <a href="cart.php" class="header__cart">🛒<span class="header__cart-badge"><?= (int)(getCart()->countUnique() ?? 0) ?></span></a>
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
            <aside class="catalog-sidebar">
                <form method="GET" action="catalog.php">
                    <div class="catalog-sidebar__section">
                        <h3 class="catalog-sidebar__title">Search</h3>
                        <input type="text" name="nameSearch" class="form-input" placeholder="Search..." value="<?= e($nameSearch) ?>">
                    </div>

                    <div class="catalog-sidebar__section">
                        <h3 class="catalog-sidebar__title">Categories</h3>
                        <div class="catalog-sidebar__categories">
                            <?php foreach ($categoryPool as $cat): ?>
                                <label class="form-checkbox">
                                    <input type="checkbox" name="categories[]" value="<?= $cat->getName() ?>" <?= in_array($cat->getName(), $selectedCategories, true) ? 'checked' : '' ?>>
                                    <span><?= e($cat->getName()) ?> (<?= (int)$cat->getCount() ?>)</span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="catalog-sidebar__section">
                        <h3 class="catalog-sidebar__title">Price</h3>
                        <div class="catalog-sidebar__price-inputs">
                            <div class="form-group">
                                <label class="form-label">Min</label>
                                <input type="number" name="price_min" class="form-input" placeholder="0$" min="0" step="0.01" value="<?= e($minPrice) ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Max</label>
                                <input type="number" name="price_max" class="form-input" placeholder="100$" min="0" step="0.01" value="<?= e($maxPrice) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="catalog-sidebar__section">
                        <h3 class="catalog-sidebar__title">Availability</h3>
                        <label class="form-checkbox">
                            <input type="checkbox" name="in_stock" value="1" <?= $inStockOnly ? 'checked' : '' ?>>
                            <span>Only in stock</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn--primary btn--block">Apply</button>
                    <a href="catalog.php" class="btn btn--secondary btn--block mt-sm">Reset</a>
                
            </aside>

            <div class="catalog-main">
                <div class="catalog-header">
                    <p><strong><?= (int)count($filtered) ?></strong> products found</p>
                    <div class="catalog-header__sort">
                        <label>Sort:</label>
                        <select name="sort" onchange="this.form.submit()">
                            <option value="az" <?= ($_GET['sort'] ?? '') === 'az' ? 'selected' : '' ?>>A → Z</option>
                            <option value="za" <?= ($_GET['sort'] ?? '') === 'za' ? 'selected' : '' ?>>Z → A</option>
                            <option value="price_asc" <?= ($_GET['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Price ↑</option>
                            <option value="price_desc" <?= ($_GET['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price ↓</option>
                        </select>
                        </form>
                    </div>
                </div>

                <div class="products-grid">
                    <?php foreach ($filtered as $p): ?>
                        <?php $stock = $p->getStock(); ?>
                        <article class="product-card">
                            <div class="product-card__image-wrapper">
                                <img src="<?= e($p->getImage()) ?>" alt="<?= e($p->getName()) ?>" class="product-card__image">
                                <div class="product-card__badges">
                                    <?php if ($p->isNew()): ?>
                                        <span class="badge badge--new">New</span>
                                    <?php endif; ?>

                                    <?php if ($p->isOnSale()): ?>
                                        <span class="badge badge--promo">-<?= (int)$p->getDiscount() ?>%</span>
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
                                <span class="product-card__category"><?= e($p->getCategory()->getName()) ?></span>
                                <a href="product.php?id=<?= urlencode((string)$p->getId()) ?>" class="product-card__title">
                                    <?= e($p->getName()) ?>
                                </a>

                                <div class="product-card__price">
                                    <?php if ($p->isOnSale()): ?>
                                        <span class="product-card__price-current"><?= formatPrice($p->getFinalPrice()) ?></span>
                                        <span class="product-card__price-old"><?= formatPrice($p->getPrice()) ?></span>
                                    <?php else: ?>
                                        <span class="product-card__price-current"><?= formatPrice($p->getPrice()) ?></span>
                                    <?php endif; ?>
                                </div>

                                <?php if ($stock <= 0): ?>
                                    <p class="product-card__stock product-card__stock--out">✗ Out of stock</p>
                                <?php elseif ($stock <= 3): ?>
                                    <p class="product-card__stock product-card__stock--low">⚠ Only <?= (int)$stock ?> left</p>
                                <?php else: ?>
                                    <p class="product-card__stock product-card__stock--available">✓ In stock (<?= (int)$stock ?>)</p>
                                <?php endif; ?>

                                <div class="product-card__actions">
                                    <?php if ($stock > 0): ?>
                                        <form action="cart.php" method="POST">
                                            <input type="hidden" name="action" value="add">
                                            <input type="hidden" name="idCart" value="<?= (int)$p->getId() ?>">

                                            <button type="button" onclick="this.nextElementSibling.stepDown()">−</button>
                                            <input type="number" name="quantityAdd" value="1" min="1" step="1">
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

                <script>
                    (function(){
                        const el = document.querySelector('.catalog-header p strong');
                        if (el) el.textContent = "<?= (int)$countTotal ?>";
                    })();
                </script>

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
