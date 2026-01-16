<?php
class CategoryRepository
{
    public function __construct(private PDO $pdo) {}

    public function find(int $id): ?Category
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, name FROM categories WHERE id = ?"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    public function findByName(string $name): ?Category
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, name FROM categories WHERE name = ?"
        );
        $stmt->execute([$name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    /** @return Category[] */
    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            "SELECT id, name FROM categories ORDER BY name"
        );

        return array_map(
            [$this, 'hydrate'],
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function save(Category $category): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO categories (name) VALUES (?)"
        );
        $stmt->execute([$category->getName()]);

        $category->setId((int)$this->pdo->lastInsertId());
    }

    private function hydrate(array $row): Category
    {
        return new Category(
            (int)$row['id'],
            (string)$row['name']
        );
    }

    public function delete(Category $category): void{
        $stmt = $this->pdo->prepare("DELETE FROM category WHERE id =?");
        $stmt->execute($category->getId());
    }

    public function update(Category $category): void{
        $stmt = $this->pdo->prepare("UPDATE category SET name = ? WHERE id = ?");
        $stmt->execute([$category->getName(), $category->getId()]);
    }

    public function findAllWithProducts(): array
    {
        $sql = "
            SELECT
                c.id   AS c_id,
                c.name AS c_name,
                p.id   AS p_id,
                p.name AS p_name,
                p.description,
                p.price,
                p.stock,
                p.discount,
                p.image,
                p.dateAdded
            FROM categories c
            LEFT JOIN products p ON p.category = c.id
            ORDER BY c.name, p.name
        ";

        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $out = [];

        foreach ($rows as $r) {
            $catId = (int)$r['c_id'];

            if (!isset($out[$catId])) {
                $out[$catId] = [
                    'category' => new Category($catId, (string)$r['c_name']),
                    'products' => [],
                ];
            }

            // LEFT JOIN: if category has no products, p_id is null
            if (!empty($r['p_id'])) {
                $category = $out[$catId]['category'];

                $out[$catId]['products'][] = new Product(
                    id: (int)$r['p_id'],
                    name: (string)$r['p_name'],
                    description: (string)($r['description'] ?? ''),
                    price: (float)$r['price'],
                    stock: (int)$r['stock'],
                    category: $category,
                    discount: (int)($r['discount'] ?? 0),
                    image: (string)($r['image'] ?? ''),
                    dateAdded: (string)($r['dateAdded'] ?? '')
                );
            }
        }

        return array_values($out);
    }
} 