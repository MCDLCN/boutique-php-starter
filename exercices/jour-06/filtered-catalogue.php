<?php
require_once __DIR__ . '/../../app/data.php';
require_once __DIR__ . '/../../app/helpers.php';

$options = array_values(array_unique(array_map(fn($p) => $p['category'], $products)));

$name     = trim($_GET['name'] ?? '');
$category = $_GET['category'] ?? '';
$maxPrice = $_GET['maxPrice'] ?? '';
$inStock  = isset($_GET['inStock']);

?>
<form method="GET" action="filtered-catalogue.php">
    <input type="text" name="name" value="<?= e($name) ?>">

    <select name="category">
        <option value="">All categories</option>
        <?php foreach ($options as $option): ?>
            <option value="<?= e($option) ?>" <?= $option === $category ? 'selected' : '' ?>>
                <?= e($option) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="number" name="maxPrice" value="<?= e($maxPrice) ?>" step="0.01">

    <label>
        <input type="checkbox" name="inStock" <?= $inStock ? 'checked' : '' ?>>
        In stock
    </label>

    <button type="submit">Filter</button>
</form>

<?php
$results = [];

foreach ($products as $product) {
    if ($name !== '' && stripos($product['name'], $name) === false) continue;
    if ($maxPrice !== '' && $product['price'] > (float)$maxPrice) continue;
    if ($category !== '' && $product['category'] !== $category) continue;
    if ($inStock && $product['stock'] <= 0) continue;

    $results[] = $product['name'];
}

echo $results ? implode('<br>', $results) : 'Nothing found';