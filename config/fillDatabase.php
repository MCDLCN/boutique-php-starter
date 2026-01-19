<?php
declare(strict_types=1);

/**
 * restock_db.php
 * Run: php restock_db.php
 * Or open it in the browser once (but CLI is better).
 */

$host = 'localhost';
$db   = 'shop';
$user = 'dev';
$pass = 'dev';

try {
    // 1) Connect WITHOUT selecting a DB first
    $pdo = new PDO(
        "mysql:host={$host};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    // 2) Create DB (if needed) + select it
    $pdo->exec("
        CREATE DATABASE IF NOT EXISTS {$db}
        CHARACTER SET utf8mb4
        COLLATE utf8mb4_unicode_ci
    ");
    $pdo->exec("USE {$db}");

    // 3) (Re)create tables fresh
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
    $pdo->exec("DROP TABLE IF EXISTS products");
    $pdo->exec("DROP TABLE IF EXISTS category");
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");

    $pdo->exec("
        CREATE TABLE category (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL UNIQUE
        ) ENGINE=InnoDB
        DEFAULT CHARSET=utf8mb4
        COLLATE=utf8mb4_unicode_ci
    ");

    $pdo->exec("
        CREATE TABLE products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            stock INT NOT NULL DEFAULT 0,
            category INT NOT NULL,
            discount INT NOT NULL DEFAULT 0,
            image VARCHAR(255),
            dateAdded DATE NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_products_category
                FOREIGN KEY (category) REFERENCES category(id)
                ON UPDATE CASCADE
                ON DELETE RESTRICT
        ) ENGINE=InnoDB
        DEFAULT CHARSET=utf8mb4
        COLLATE=utf8mb4_unicode_ci
    ");

    // 4) Load your old array data
    require_once __DIR__ . '/../app/data.php'; // <-- adjust if needed

    if (!isset($products) || !is_array($products)) {
        throw new RuntimeException("data.php must define \$products as an array.");
    }

    $pdo->beginTransaction();

    // 5) Insert category (unique)
    $insertCategory = $pdo->prepare("INSERT IGNORE INTO category (name) VALUES (?)");

    $seen = [];
    foreach ($products as $p) {
        $catName = (string)($p['category'] ?? '');
        $catName = trim($catName);
        if ($catName === '') continue;

        $key = mb_strtolower($catName);
        if (isset($seen[$key])) continue;
        $seen[$key] = true;

        $insertCategory->execute([$catName]);
    }

    // 6) Build category name -> id map
    $categoryMap = [];
    $rows = $pdo->query("SELECT id, name FROM category")->fetchAll();
    foreach ($rows as $r) {
        $categoryMap[(string)$r['name']] = (int)$r['id'];
    }

    // 7) Insert products using category
    $insertProduct = $pdo->prepare("
        INSERT INTO products (name, description, price, stock, category, discount, image, dateAdded)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($products as $p) {
        $catName = (string)($p['category'] ?? '');
        $catName = trim($catName);

        if ($catName === '' || !isset($categoryMap[$catName])) {
            throw new RuntimeException("Missing/unknown category for product: " . (string)($p['name'] ?? '(no name)'));
        }

        $insertProduct->execute([
            (string)($p['name'] ?? ''),
            (string)($p['description'] ?? ''),
            (float)($p['price'] ?? 0),
            (int)($p['stock'] ?? 0),
            (int)$categoryMap[$catName],
            (int)($p['discount'] ?? 0),
            (string)($p['image'] ?? ''),
            isset($p['dateAdded']) && $p['dateAdded'] !== '' ? (string)$p['dateAdded'] : null,
        ]);
    }

    $pdo->commit();

    echo "✅ Database restocked successfully.\n";
    echo "category: " . (int)$pdo->query("SELECT COUNT(*) FROM category")->fetchColumn() . "\n";
    echo "Products:   " . (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn() . "\n";

} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}