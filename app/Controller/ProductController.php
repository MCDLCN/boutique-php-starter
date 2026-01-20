<?php
namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Database;

class ProductController
{
    private CategoryRepository $catRepo;
    private ProductRepository $repository;
    public function __construct()
    {
        $pdo = Database::getInstance();
        $this->catRepo = new CategoryRepository($pdo);
        $this->repository = new ProductRepository($pdo, $this->catRepo);
    }

    // GET /produits
    public function index(): void
    {
        $products = $this->repository->findAll();
        require __DIR__ . '/../../views/products/index.php';
    }

    // GET /produit?id=X
    public function show(array $params): void
    {
        $id = (int)$params['id'];
        if (!$id) {
            $this->redirect('/products');
            return;
        }

        $product = $this->repository->find((int) $id);
        
        if (!$product) {
            http_response_code(404);
            require __DIR__ . '/../../views/errors/404.php';
            return;
        }

        require __DIR__ . '/../../views/products/show.php';
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}