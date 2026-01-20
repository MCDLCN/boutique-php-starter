<?php
namespace App\Repository;

use PDO;
use App\Entity\Product;
use App\Entity\Category;

use App\Repository\RepositoryInterface;
class ProductRepository implements RepositoryInterface 
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
    public function save(object $entity): void
    {
        $stmt = $this->pdo->prepare(
        "INSERT INTO products (name, description, price, stock, category, discount, image, dateAdded)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $entity->getName(),
            $entity->getDescription(),
            $entity->getPrice(),
            $entity->getStock(),
            $entity->getCategory()->getId(),
            $entity->getDiscount(),
            $entity->getImage(),
            $entity->getDateAdded(),
        ]);
        $entity->setId((int) $this->pdo->lastInsertId());
    }
    
    // UPDATE
    public function update(Product $product): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category = ?, discount = ?, image = ? WHERE id = ?"
        );
        $stmt->execute([
            $product->getName(),
            $product->getDescription(),
            $product->getPrice(),
            $product->getStock(),
            $product->getCategory()->getId(),
            $product->getDiscount(),
            $product->getImage(),
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

    public function findByCategory(Category $category): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM products WHERE category = ?"
        );
        $stmt->execute([$category->getId()]);
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

    public function findPaginated(int $page, int $perPage = 10): array {
        $page=max(1, $page);
        $perPage=max(1, $perPage);
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->pdo->prepare(
            "SELECT * FROM products LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }
    
    public function count(): int {
        $stmt= $this->pdo->query('SELECT COUNT(id) FROM products');
        return (int) $stmt->fetchColumn();
    }
    
    public function getPaginationData(int $page, int $perPage = 10): array {
        $total = $this->count();
        $page=max(1, $page);
        $perPage=max(1, $perPage);
        return [
            'currentPage' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => (int) ceil($total / $perPage),
            'hasNext' => $page * $perPage < $total,
            'hasPrevious' => $page > 1,
        ];
    }

    public function findPaginatedFiltered(int $page, int $perPage = 10, array $filters = []): array
{
    $page = max(1, $page);
    $perPage = max(1, $perPage);
    $offset = ($page - 1) * $perPage;

    [$whereSql, $params, $needsCategoryJoin] = $this->buildFiltersWhere($filters);

    $orderBy = $this->buildOrderBy($filters['sort'] ?? 'az');

    $sql = "
        SELECT p.*
        FROM products p
        " . ($needsCategoryJoin ? "JOIN category c ON c.id = p.category" : "") . "
        $whereSql
        $orderBy
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $this->pdo->prepare($sql);

    // bind filter params
    foreach ($params as $key => $value) {
        // ints/bools usually safe as int, others as string
        if (is_int($value)) {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value);
        }
    }

    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();

    return array_map([$this, 'hydrate'], $stmt->fetchAll());
}

public function countFiltered(array $filters = []): int
{
    [$whereSql, $params, $needsCategoryJoin] = $this->buildFiltersWhere($filters);

    $sql = "
        SELECT COUNT(*)
        FROM products p
        " . ($needsCategoryJoin ? "JOIN category c ON c.id = p.category" : "") . "
        $whereSql
    ";

    $stmt = $this->pdo->prepare($sql);

    foreach ($params as $key => $value) {
        if (is_int($value)) {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value);
        }
    }

    $stmt->execute();
    return (int) $stmt->fetchColumn();
}

    public function getPaginationDataFiltered(int $page, int $perPage = 10, array $filters = []): array
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);

        $total = $this->countFiltered($filters);
        $totalPages = (int) ceil($total / $perPage);

        return [
            'currentPage' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => $totalPages,
            'hasNext' => $page < $totalPages,
            'hasPrevious' => $page > 1,
        ];
    }

    /**
     * Returns: [whereSql, params, needsCategoryJoin]
     */
    private function buildFiltersWhere(array $filters): array
    {
        $where = [];
        $params = [];
        $needsCategoryJoin = false;

        // Search by name
        if (!empty($filters['nameSearch'])) {
            $where[] = 'p.name LIKE :nameSearch';
            $params[':nameSearch'] = '%' . $filters['nameSearch'] . '%';
        }

        // In stock only
        if (!empty($filters['inStock'])) {
            $where[] = 'p.stock > 0';
        }

        // Price range based on FINAL price
        $finalPrice = '(p.price * (1 - (COALESCE(p.discount, 0) / 100)))';

        if ($filters['priceMin'] !== '' && $filters['priceMin'] !== null) {
            $where[] = "$finalPrice >= :priceMin";
            $params[':priceMin'] = (float) $filters['priceMin'];
        }

        if ($filters['priceMax'] !== '' && $filters['priceMax'] !== null) {
            $where[] = "$finalPrice <= :priceMax";
            $params[':priceMax'] = (float) $filters['priceMax'];
        }

        // Categories by NAME (requires join)
        if (!empty($filters['categories']) && is_array($filters['categories'])) {
            $needsCategoryJoin = true;

            $in = [];
            foreach (array_values($filters['categories']) as $i => $catName) {
                $ph = ':cat' . $i;
                $in[] = $ph;
                $params[$ph] = (string) $catName;
            }

            if (!empty($in)) {
                $where[] = 'c.name IN (' . implode(',', $in) . ')';
            }
        }

        $whereSql = '';
        if (!empty($where)) {
            $whereSql = 'WHERE ' . implode(' AND ', $where);
        }

        return [$whereSql, $params, $needsCategoryJoin];
    }

    private function buildOrderBy(string $sort): string
    {
        $finalPrice = '(p.price * (1 - (COALESCE(p.discount, 0) / 100)))';

        return match ($sort) {
            'za'         => 'ORDER BY p.name DESC',
            'price_asc'  => "ORDER BY $finalPrice ASC",
            'price_desc' => "ORDER BY $finalPrice DESC",
            default      => 'ORDER BY p.name ASC', // 'az'
        };
    }

}