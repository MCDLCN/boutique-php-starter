<?php

//namespace App\Controller;

use App\Database;
use App\Entity\Cart;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;

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
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $pdo = Database::getInstance();
        $categoryRepo = new CategoryRepository($pdo);
        $productRepo  = new ProductRepository($pdo, $categoryRepo);

        // cart
        if (!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof Cart)) {
            $_SESSION['cart'] = new Cart();
        }
        $cart = $_SESSION['cart'];

        // pagination + filters
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = isset($_GET['perPage']) ? (int)$_GET['perPage'] : 10;
        $allowedPerPage = [10, 15, 20, 25];
        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        $filters = [
            'nameSearch' => (string)($_GET['nameSearch'] ?? ''),
            'categories' => $_GET['categories'] ?? [],
            'priceMin'   => (string)($_GET['price_min'] ?? ''),
            'priceMax'   => (string)($_GET['price_max'] ?? ''),
            'inStock'    => isset($_GET['in_stock']),
            'sort'       => (string)($_GET['sort'] ?? 'az'),
        ];
        $products = $productRepo->findAll();
        $categoryCounts = [];
        foreach ($products as $p) {
            $name = $p->getCategory()->getName();
            $categoryCounts[$name] = ($categoryCounts[$name] ?? 0) + 1;
        }
        // Counters
        $inStock = 0;
        $onSale = 0;
        $outOfStock = 0;

        foreach ($products as $p) {
            $p->isInStock() ? $inStock++ : $outOfStock++;
            if ($p->isOnSale()) {
                $onSale++;
            }
        }

        $productsFound = $productRepo->countFiltered($filters);

        $products    = $productRepo->findPaginatedFiltered($page, $perPage, $filters);
        $pagination  = $productRepo->getPaginationDataFiltered($page, $perPage, $filters);
        $categories  = $categoryRepo->findAll();

        $currentlyHere = 'catalog';

        view('products/index', [
            'products'        => $products,
            'pagination'      => $pagination,
            'categories'      => $categories,
            'filters'         => $filters,
            'categoryCounts'  => $categoryCounts,
            'inStock'         => $inStock,
            'onSale'          => $onSale,
            'outOfStock'      => $outOfStock,
            'productsFound'   => $productsFound,
            'currentlyHere'   => $currentlyHere,
            'cart'            => $cart,
            'perPage'         => $perPage
        ]);
    }

    // GET /produit?id=X
    public function show(array $params): void
    {
        $id = (int)$params['id'];
        if ($id === 0) {
            $this->redirect('/products');
            return;
        }

        $product = $this->repository->find((int) $id);

        if (!$product instanceof \App\Entity\Product) {
            http_response_code(404);
            require __DIR__ . '/../../views/errors/404.php';
            return;
        }
        $currentlyHere = '';
        view('products/show', ['product' => $product,'currentlyHere' => '']);
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}
