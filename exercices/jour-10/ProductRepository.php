<?php
require_once __DIR__ ."/../../app/entities/Product.php";
require_once __DIR__ ."/../../app/entities/Category.php";
class ProductRepository
{
    public function __construct(
        private PDO $pdo,
        private CategoryRepository $categoryRepo
    ) {}
    
    // READ - Un seul
    public function find(int $id): ?Product
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? $this->hydrate($data) : null;
    }
    
    // READ - Tous
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    // CREATE
    public function save(Product $product): void
    {
        $stmt = $this->pdo->prepare(
        "INSERT INTO products (name, description, price, stock, category, discount, image, dateAdded)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $product->getName(),
            $product->getDescription(),
            $product->getPrice(),
            $product->getStock(),
            $product->getCategory()->getName(),
            $product->getDiscount(),
            $product->getImage(),
            $product->getDateAdded(),
        ]);
        $product->setId((int) $this->pdo->lastInsertId());
    }
    
    // UPDATE
    public function update(Product $product): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE products SET name = ?, price = ?, stock = ? WHERE id = ?"
        );
        $stmt->execute([
            $product->getName(),
            $product->getPrice(),
            $product->getStock(),
            $product->getId()
        ]);
    }
    
    // DELETE
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
    }
    
    // Hydratation : tableau → objet
    private function hydrate(array $data): Product
    {
        $category = $this->categoryRepo->find((int) $data['category']);

        return new Product(
            id: (int) $data['id'],
            name: (string) $data['name'],
            description: (string) $data['description'] ?? '',
            price: (float) $data['price'],
            stock: (int) $data['stock'],
            category: $category,
            discount: (int) $data['discount'] ?? 0,
            image: (string) $data['image'] ?? '',
            dateAdded: (string) $data['dateAdded'] ?? ''
        );
    }

    public function findByCategory(string $category): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM products WHERE category = ?"
        );
        $stmt->execute([$category]);
        return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    public function findInStock(): array
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM products WHERE stock > 0"
        );
        return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    public function search(string $term): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM products WHERE name LIKE ?"
        );
        $stmt->execute(['%' . $term . '%']);
        return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function findByPriceRange(float $min, float $max):array{
        $stmt = $this->pdo->prepare(
            "SELECT * FROM products WHERE price>=? AND price<=?"
        );
        $stmt->execute([$min, $max]);
        return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function findWithProduct(): array{
        $stmt = $this->pdo->prepare(
            "SELECT * FROM products GROUP BY category"
        );
        $stmt->execute();
        return array_map([$this,"hydrate"], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}

$pdo = new PDO('mysql:host=localhost;dbname=shop;charset=utf8mb4','dev', 'dev');

$productRepo = new ProductRepository($pdo);

$product1 = $productRepo->find(1);
$products = $productRepo->findAll();

// //Classic search and fetchAll
// echo $product1;
// echo '<br>';
// foreach ($products as $product) {
//     echo '<br>';
//     echo $product;
// }


// //Add, update, remove product
// echo '<br>';
// $productTest = new Product(16,"aaaaa", "category", 5.5, 700, new Category('clothes'), 90, "image", strtotime("now"));
// $productRepo->save($productTest);
// $products = $productRepo->findAll();
// echo $products[15];
// echo '<br>';
// $productTest->setPrice(0);
// $productRepo->update($productTest);
// $products = $productRepo->findAll();
// echo $products[15];
// echo '<br>';
// $productRepo->delete(16);
// echo "<br>";
// $products = $productRepo->findAll();
// echo '<br>';
// echo '<br>';
// foreach ($products as $product) {
//     echo '<br>';
//     echo $product;
// }

// //by category
// echo '<br>';
// $products = $productRepo->findByCategory('clothes');
// foreach ($products as $product) {
//     echo '<br>';
//     echo $product;
// }
// //is in stock
// echo '<br>';
// $products= $productRepo->findInStock();
// foreach ($products as $product) {
//     echo '<br>';
//     echo $product;
// }

// //price range
// echo '<br>';
// $products = $productRepo->findByPriceRange(100,10000000);
// foreach ($products as $product) {
//     echo '<br>';
//     echo $product;
// }

// echo '<br>';
// $products = $productRepo->search('a');
// foreach ($products as $product) {
//     echo '<br>';
//     echo $product;
// }

// $products = $productRepo->findWithProduct();
// foreach ($products as $product) {
//     echo '<br>';
//     echo $product;
// }