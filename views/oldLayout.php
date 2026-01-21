<!-- views/layout.php -->
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
            <a href="/" class="header__nav-link <?= ($currentlyHere === "home") ? "header__nav-link--active" : "" ?>">Home</a>
            <a href="/catalog" class="header__nav-link <?= ($currentlyHere === "catalog") ? "header__nav-link--active" : "" ?>">Catalog</a>
            <a href="contact.html" class="header__nav-link <?= ($currentlyHere === "contact") ? "header__nav-link--active" : "" ?></a>">Contact</a>
        </nav>
        <div class="header__actions">
            <a href="/cart" class="header__cart">🛒<span class="header__cart-badge"><?= isset($_SESSION['cart']) ? (int) $_SESSION['cart']->countUnique() : 0?></span></a>
            <a href="connexion.html" class="btn btn--primary btn--sm">Log in</a>
        </div>
        <button class="header__menu-toggle">☰</button>
    </div>
</header>
    
    <main>
        <?php $flash = getFlash();?>
        <?php if (isset($flash)): ?>
            <?php $type = $flash['type']; ?>
            <?php $style = '';
            if ($type === 'Success') {
                $style = 'background:#9bd49aff; border:1px solid #5fe15dff;';
            }
            if ($type === 'Error') {
                $style = 'background:#F4E2E2; border:1px solid #E6ADA9;';
            }
            ?>
                <div class="alert alert--info" style="<?= e($style) ?> color:black">
                <?= e($type) ?>: 
                <?= e((string)$flash['message']) ?>
            </div>
        <?php endif; ?>
        
        <?= $content ?>
    </main>
    
    <footer>
        <p>&copy; <?= date('Y') ?> My shop</p>
    </footer>
</body>
</html>