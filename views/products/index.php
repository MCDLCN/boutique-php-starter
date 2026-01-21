<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Our Catalog</h1>
            <p class="page-subtitle">Discover all out products</p>
            <p><strong><?= (int)$inStock ?></strong> in stock · <strong><?= (int)$outOfStock ?></strong> out of stock · <strong><?= (int)$onSale ?></strong> on sale</p>
        </div>

        <div class="catalog-layout">
            <aside class="catalog-sidebar">

                <!-- FILTER FORM -->
                <form method="GET" action="/catalog">
                    <input type="hidden" name="sort" value="<?= e($filters['sort']) ?>">

                    <div class="catalog-sidebar__section">
                        <h3 class="catalog-sidebar__title">Search</h3>
                        <input type="text" name="nameSearch" class="form-input" placeholder="Search..." value="<?= e($filters['nameSearch']) ?>">
                    </div>

                    <div class="catalog-sidebar__section">
                        <h3 class="catalog-sidebar__title">Categories</h3>
                        <div class="catalog-sidebar__categories">
                            <?php foreach ($categories as $cat): ?>
                                <?php $catName = $cat->getName(); ?>
                                <label class="form-checkbox">
                                    <input
                                        type="checkbox"
                                        name="categories[]"
                                        value="<?= e($catName) ?>"
                                        <?= in_array($catName, $filters['categories'], true) ? 'checked' : '' ?>
                                    >
                                    <span>
                                        <?= e($catName) ?>
                                        (<?= (int)($categoryCounts[$catName] ?? 0) ?>)
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="catalog-sidebar__section">
                        <h3 class="catalog-sidebar__title">Price</h3>
                        <div class="catalog-sidebar__price-inputs">
                            <div class="form-group">
                                <label class="form-label">Min</label>
                                <input type="number" name="price_min" class="form-input" placeholder="0$" min="0" step="0.01" value="<?= e($filters['priceMin']) ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Max</label>
                                <input type="number" name="price_max" class="form-input" placeholder="100$" min="0" step="0.01" value="<?= e($filters['priceMax']) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="catalog-sidebar__section">
                        <h3 class="catalog-sidebar__title">Availability</h3>
                        <label class="form-checkbox">
                            <input type="checkbox" name="in_stock" value="1" <?= $filters['inStock'] ? 'checked' : '' ?>>
                            <span>Only in stock</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn--primary btn--block">Apply</button>
                    <a href="/catalog" class="btn btn--secondary btn--block mt-sm">Reset</a>
                </form>

            </aside>

            <div class="catalog-main">
                <div class="catalog-header">
                    <p><strong><?= (int)$productsFound ?></strong> products found</p>

                    <!-- SORT FORM (keeps existing filters) -->
                    <div class="catalog-header__sort">
                        <form method="GET" action="/catalog">
                            <?php foreach ((array)$filters['categories'] as $c): ?>
                                <input type="hidden" name="categories[]" value="<?= e((string)$c) ?>">
                            <?php endforeach; ?>
                            <input type="hidden" name="nameSearch" value="<?= e($filters['nameSearch']) ?>">
                            <input type="hidden" name="price_min" value="<?= e($filters['priceMin']) ?>">
                            <input type="hidden" name="price_max" value="<?= e($filters['priceMax']) ?>">
                            <?php if ($filters['inStock']): ?>
                                <input type="hidden" name="in_stock" value="1">
                            <?php endif; ?>

                            <label>Sort:</label>
                            <select name="sort" onchange="this.form.submit()">
                                <option value="az" <?= $filters['sort'] === 'az' ? 'selected' : '' ?>>A → Z</option>
                                <option value="za" <?= $filters['sort'] === 'za' ? 'selected' : '' ?>>Z → A</option>
                                <option value="price_asc" <?= $filters['sort'] === 'price_asc' ? 'selected' : '' ?>>Price ↑</option>
                                <option value="price_desc" <?= $filters['sort'] === 'price_desc' ? 'selected' : '' ?>>Price ↓</option>
                            </select>
                        </form>
                        <form method="GET" action="/catalog">
                        <?php
                        // Preserve existing filters
                        foreach ((array)($_GET['categories'] ?? []) as $c) {
                            echo '<input type="hidden" name="categories[]" value="' . e((string)$c) . '">';
                        }
            ?>
                        <input type="hidden" name="nameSearch" value="<?= e((string)($filters['nameSearch'])) ?>">
                        <input type="hidden" name="price_min" value="<?= e((string)($_GET['price_min'] ?? '')) ?>">
                        <input type="hidden" name="price_max" value="<?= e((string)($_GET['price_max'] ?? '')) ?>">
                        <?php if (isset($_GET['in_stock'])): ?>
                            <input type="hidden" name="in_stock" value="1">
                        <?php endif; ?>
                        <input type="hidden" name="sort" value="<?= e((string)($_GET['sort'] ?? 'az')) ?>">
                        <input type="hidden" name="page" value="1"><!-- reset page when perPage changes -->

                        <label>Per page:</label>
                        <select name="perPage" onchange="this.form.submit()">
                            <?php foreach ([10, 15, 20, 25] as $n): ?>
                                <option value="<?= $n ?>" <?= $perPage === $n ? 'selected' : '' ?>><?= $n ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                    </div>
                </div>

                <div class="products-grid">
                    <?php foreach ($products as $p): ?>
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
                                <a href="/product/<?= urlencode((string)$p->getId()) ?>" class="product-card__title">
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
                                        <form action="/cart/add" method="POST">
                                            <input type="hidden" name="action" value="add">
                                            <input type="hidden" name="idCart" value="<?= (int)$p->getId() ?>">
                                            <input type="hidden" name="redirect" value="<?= e($_SERVER['REQUEST_URI']) ?>">

                                            <button type="button" onclick="this.nextElementSibling.stepDown()">−</button>
                                            <input type="number" name="quantityAdd" value="<?= old('quantityAdd', 1) ?>" min="1" step="1">
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

                <nav class="pagination">
                    <?php if ($pagination['hasPrevious']): ?>
                        <a class="pagination__item" href="<?= e(pageUrl($pagination['currentPage'] - 1)) ?>">←</a>
                    <?php else: ?>
                        <span class="pagination__item pagination__item--disabled">←</span>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
                        <a
                            class="pagination__item <?= $i === $pagination['currentPage'] ? 'pagination__item--active' : '' ?>"
                            href="<?= e(pageUrl($i)) ?>"
                        >
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($pagination['hasNext']): ?>
                        <a class="pagination__item" href="<?= e(pageUrl($pagination['currentPage'] + 1)) ?>">→</a>
                    <?php else: ?>
                        <span class="pagination__item pagination__item--disabled">→</span>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </div>
</main>
