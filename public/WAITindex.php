<?php
// public/index.php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Database;

// Récupérer l'URL demandée
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Charger les routes et dispatcher
$router = require __DIR__ . '/../config/routes.php';
$router->dispatch($uri, $method);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MaBoutique - Accueil</title>
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
            <a href="index.html" class="header__nav-link header__nav-link--active">Accueil</a>
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

        <!-- HERO -->
        <section class="hero">
            <div class="container hero__content">
                <h1 class="hero__title">Bienvenue sur MaBoutique</h1>
                <p class="hero__subtitle">Découvrez notre collection de vêtements, chaussures et accessoires de qualité.</p>
                <div class="hero__actions">
                    <a href="catalogue.html" class="btn btn--secondary btn--lg">Voir le catalogue</a>
                    <a href="#produits" class="btn btn--outline btn--lg">Nouveautés</a>
                </div>
            </div>
        </section>

        <!-- ============================================
             STATISTIQUES
             JOUR 4 : Calculer avec conditions
             ============================================ -->
        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-card__icon stat-card__icon--primary">📦</div>
                <div class="stat-card__content">
                    <!-- JOUR 4 : <?= count($products) ?> -->
                    <div class="stat-card__value">8</div>
                    <div class="stat-card__label">Produits</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon stat-card__icon--success">✅</div>
                <div class="stat-card__content">
                    <!-- JOUR 4 : Compter stock > 0 -->
                    <div class="stat-card__value">6</div>
                    <div class="stat-card__label">En stock</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon stat-card__icon--warning">🏷️</div>
                <div class="stat-card__content">
                    <!-- JOUR 4 : Compter discount > 0 -->
                    <div class="stat-card__value">2</div>
                    <div class="stat-card__label">En promo</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon stat-card__icon--secondary">📂</div>
                <div class="stat-card__content">
                    <div class="stat-card__value">3</div>
                    <div class="stat-card__label">Catégories</div>
                </div>
            </div>
        </section>

        <!-- ============================================
             PRODUITS VEDETTES
             JOUR 2 : Créer $products dans app/data.php
             JOUR 3 : Générer avec foreach
             JOUR 4 : Badges conditionnels
             JOUR 7 : Formulaire POST ajout panier
             ============================================ -->
        <section id="produits">
            <div class="section-header">
                <h2 class="section-title">Produits vedettes</h2>
                <a href="catalogue.html" class="section-link">Voir tout →</a>
            </div>

            <!-- JOUR 3 : <?php foreach ($products as $product): ?> -->
            <div class="products-grid">

                <!-- Produit 1 : NOUVEAU -->
                <article class="product-card">
                    <div class="product-card__image-wrapper">
                        <img src="https://via.placeholder.com/300x300/e2e8f0/64748b?text=T-shirt" alt="T-shirt Premium Bio" class="product-card__image">
                        <div class="product-card__badges">
                            <!-- JOUR 4 : <?php if ($product['is_new']): ?> -->
                            <span class="badge badge--new">Nouveau</span>
                            <!-- <?php endif; ?> -->
                        </div>
                    </div>
                    <div class="product-card__content">
                        <span class="product-card__category">Vêtements</span>
                        <a href="produit.html" class="product-card__title">T-shirt Premium Bio</a>
                        <div class="product-card__price">
                            <!-- JOUR 5 : <?= formatPrice($product['price']) ?> -->
                            <span class="product-card__price-current">35,99 €</span>
                        </div>
                        <p class="product-card__stock product-card__stock--available">✓ En stock (45)</p>
                        <div class="product-card__actions">
                            <!-- JOUR 7 : Formulaire POST -->
                            <form action="panier.html" method="POST">
                                <input type="hidden" name="product_id" value="1">
                                <input type="hidden" name="action" value="add">
                                <button type="submit" class="btn btn--primary btn--block">Ajouter au panier</button>
                            </form>
                        </div>
                    </div>
                </article>

                <!-- Produit 2 : PROMO + DERNIERS -->
                <article class="product-card">
                    <div class="product-card__image-wrapper">
                        <img src="https://via.placeholder.com/300x300/e2e8f0/64748b?text=Sneakers" alt="Sneakers Urban" class="product-card__image">
                        <div class="product-card__badges">
                            <span class="badge badge--promo">-20%</span>
                            <span class="badge badge--low-stock">Derniers</span>
                        </div>
                    </div>
                    <div class="product-card__content">
                        <span class="product-card__category">Chaussures</span>
                        <a href="produit.html" class="product-card__title">Sneakers Urban</a>
                        <div class="product-card__price">
                            <span class="product-card__price-current">79,99 €</span>
                            <span class="product-card__price-old">99,99 €</span>
                        </div>
                        <p class="product-card__stock product-card__stock--low">⚠ Plus que 3</p>
                        <div class="product-card__actions">
                            <form action="panier.html" method="POST">
                                <input type="hidden" name="product_id" value="2">
                                <input type="hidden" name="action" value="add">
                                <button type="submit" class="btn btn--primary btn--block">Ajouter au panier</button>
                            </form>
                        </div>
                    </div>
                </article>

                <!-- Produit 3 : NOUVEAU -->
                <article class="product-card">
                    <div class="product-card__image-wrapper">
                        <img src="https://via.placeholder.com/300x300/e2e8f0/64748b?text=Sac" alt="Sac à dos Urbain" class="product-card__image">
                        <div class="product-card__badges">
                            <span class="badge badge--new">Nouveau</span>
                        </div>
                    </div>
                    <div class="product-card__content">
                        <span class="product-card__category">Accessoires</span>
                        <a href="produit.html" class="product-card__title">Sac à dos Urbain</a>
                        <div class="product-card__price">
                            <span class="product-card__price-current">59,99 €</span>
                        </div>
                        <p class="product-card__stock product-card__stock--available">✓ En stock (12)</p>
                        <div class="product-card__actions">
                            <form action="panier.html" method="POST">
                                <input type="hidden" name="product_id" value="5">
                                <input type="hidden" name="action" value="add">
                                <button type="submit" class="btn btn--primary btn--block">Ajouter au panier</button>
                            </form>
                        </div>
                    </div>
                </article>

                <!-- Produit 4 : RUPTURE -->
                <article class="product-card">
                    <div class="product-card__image-wrapper">
                        <img src="https://via.placeholder.com/300x300/e2e8f0/64748b?text=Montre" alt="Montre Classic" class="product-card__image">
                        <div class="product-card__badges">
                            <span class="badge badge--out-of-stock">Rupture</span>
                        </div>
                    </div>
                    <div class="product-card__content">
                        <span class="product-card__category">Accessoires</span>
                        <a href="produit.html" class="product-card__title">Montre Classic</a>
                        <div class="product-card__price">
                            <span class="product-card__price-current">89,99 €</span>
                        </div>
                        <p class="product-card__stock product-card__stock--out">✗ Rupture de stock</p>
                        <div class="product-card__actions">
                            <!-- JOUR 4 : disabled si stock === 0 -->
                            <button type="button" class="btn btn--secondary btn--block" disabled>Indisponible</button>
                        </div>
                    </div>
                </article>

            </div>
            <!-- JOUR 3 : <?php endforeach; ?> -->
        </section>

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
                <h4>À propos</h4>
                <p>MaBoutique - Votre destination shopping en ligne.</p>
            </div>
            <div class="footer__section">
                <h4>Navigation</h4>
                <ul>
                    <li><a href="index.html">Accueil</a></li>
                    <li><a href="catalogue.html">Catalogue</a></li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
            </div>
            <div class="footer__section">
                <h4>Mon compte</h4>
                <ul>
                    <li><a href="connexion.html">Connexion</a></li>
                    <li><a href="inscription.html">Inscription</a></li>
                    <li><a href="panier.html">Mon panier</a></li>
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
            <p>&copy; 2024 MaBoutique - Formation PHP 14 jours</p>
        </div>
    </div>
</footer>

</body>
</html>
