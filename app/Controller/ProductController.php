<?php

namespace App\Controller;

use App\Database;
use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
class ProductController extends Controller
{
    private CategoryRepository $catRepo;
    private ProductRepository $repository;
    private ?ReviewRepository $reviewRepo = null;

    public function __construct()
    {
        $pdo = Database::getInstance();
        $this->catRepo = new CategoryRepository($pdo);
        $this->repository = new ProductRepository($pdo, $this->catRepo);
    }

    private function getReviewRepository(): ReviewRepository
    {
        if (!$this->reviewRepo instanceof \App\Repository\ReviewRepository) {
            $pdo = Database::getInstance();
            $productRepo = $this->repository;
            $userRepo = new UserRepository($pdo, new \App\Repository\AddressRepository($pdo));
            $this->reviewRepo = new ReviewRepository($pdo, $productRepo, $userRepo);
        }
        return $this->reviewRepo;
    }

    private function cart(): Cart
    {
        /** @var Cart $cart */
        $cart = getCart();

        return $cart;
    }

    // GET /produits
    public function index(): void
    {
        $cart = $this->cart();

        // pagination + filters
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;

        $perPage = isset($_GET['perPage']) ? (int) $_GET['perPage'] : 10;
        $allowedPerPage = [10, 15, 20, 25];
        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        $filters = [
            'nameSearch' => (string) ($_GET['nameSearch'] ?? ''),
            'categories' => $_GET['categories'] ?? [],
            'priceMin'   => (string) ($_GET['price_min'] ?? ''),
            'priceMax'   => (string) ($_GET['price_max'] ?? ''),
            'inStock'    => isset($_GET['in_stock']),
            'sort'       => (string) ($_GET['sort'] ?? 'az'),
        ];

        // category counts + counters (based on full catalog)
        $allProducts = $this->repository->findAll();

        $categoryCounts = [];
        $inStock = 0;
        $onSale = 0;
        $outOfStock = 0;

        foreach ($allProducts as $p) {
            $catName = $p->getCategory()->getName();
            $categoryCounts[$catName] = ($categoryCounts[$catName] ?? 0) + 1;

            if ($p->isInStock()) {
                $inStock++;
            } else {
                $outOfStock++;
            }

            if ($p->isOnSale()) {
                $onSale++;
            }
        }

        // filtered list + pagination
        $productsFound = $this->repository->countFiltered($filters);
        $products = $this->repository->findPaginatedFiltered($page, $perPage, $filters);
        $pagination = $this->repository->getPaginationDataFiltered($page, $perPage, $filters);

        $categories = $this->catRepo->findAll();

        $this->view('products/index', [
            'products'       => $products,
            'pagination'     => $pagination,
            'categories'     => $categories,
            'filters'        => $filters,
            'categoryCounts' => $categoryCounts,
            'inStock'        => $inStock,
            'onSale'         => $onSale,
            'outOfStock'     => $outOfStock,
            'productsFound'  => $productsFound,
            'currentlyHere'  => 'catalog',
            'cart'           => $cart,
            'perPage'        => $perPage,
        ]);
    }

    // GET /produit/{id}
    /**
     * @param mixed[] $params
     */
    public function show(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('/products');
        }

        $product = $this->repository->find($id);

        if (!$product instanceof Product) {
            http_response_code(404);
            $this->view('errors/404', [
                'currentlyHere' => '',
            ]);
            return;
        }

        // Get reviews for this product
        $reviews = $this->getReviewRepository()->findByProduct($id);

        // Check if user already reviewed this product
        $userReview = null;
        if (isset($_SESSION['user_id'])) {
            $userReview = $this->getReviewRepository()->findByProductAndUser($id, $_SESSION['user_id']);
        }

        $this->view('products/show', [
            'product' => $product,
            'reviews' => $reviews,
            'userReview' => $userReview,
            'currentlyHere' => '',
        ]);
    }

    // POST /product/{id}/review
    /**
     * @param mixed[] $params
     */
    public function submitReview(array $params): void
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'You must be logged in to review']);
            exit;
        }

        $productId = (int) ($params['id'] ?? 0);
        $rating = (int) ($_POST['rating'] ?? 0);
        $comment = (string) ($_POST['comment'] ?? '');

        if ($productId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid product']);
            exit;
        }

        if ($comment === '' || $comment === '0') {
            echo json_encode(['success' => false, 'message' => 'Comment is required']);
            exit;
        }

        $product = $this->repository->find($productId);
        if (!$product instanceof Product) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit;
        }

        $pdo = Database::getInstance();
        $userRepo = new UserRepository($pdo, new \App\Repository\AddressRepository($pdo));
        $user = $userRepo->find($_SESSION['user_id']);

        if (!$user instanceof User) {
            echo json_encode(['success' => false, 'message' => 'User not found']);
            exit;
        }

        try {
            // Check if user already has a review for this product
            $existingReview = $this->getReviewRepository()->findByProductAndUser($productId, $_SESSION['user_id']);

            if ($existingReview instanceof \App\Entity\Review) {
                // Update existing review
                $existingReview->setRating($rating);
                $existingReview->setComment($comment);
                $existingReview->setUpdatedDate(date('Y-m-d H:i:s'));
                $this->getReviewRepository()->update($existingReview);
                echo json_encode(['success' => true, 'message' => 'Review updated successfully']);
            } else {
                // Create new review
                $createdDate = date('Y-m-d H:i:s');
                $review = new \App\Entity\Review(
                    rating: $rating,
                    comment: $comment,
                    createdDate: $createdDate,
                    product: $product,
                    user: $user
                );
                $this->getReviewRepository()->save($review);
                echo json_encode(['success' => true, 'message' => 'Review submitted successfully']);
            }
            exit;
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }

    // POST /cart/add (from catalog or product page)
    // If you don't have this route, ignore this method.
    // public function addToCart(): void
    // {
    //     $cart = $this->cart();

    //     $id = (int) ($_POST['idCart'] ?? $_POST['id'] ?? 0);
    //     $qty = max(1, (int) ($_POST['quantityAdd'] ?? 1));
    //     $redirectTo = (string) ($_POST['redirect'] ?? '/catalog');

    //     if ($id <= 0) {
    //         setSession('old', $_POST);
    //         redirect($redirectTo);
    //     }

    //     $product = $this->repository->find($id);
    //     if (!$product) {
    //         http_response_code(404);
    //         exit('No such product');
    //     }

    //     $item = $cart->getCartItem($id);

    //     if ($item !== null) {
    //         $current = $item->getQuantity();

    //         if ($product->canAddToCart($qty, $current)) {
    //             $item->setQuantity($current + $qty);
    //             flash('Success', 'Added to cart');
    //         } else {
    //             flash('Error', 'Not enough stock');
    //             setSession('old', $_POST);
    //         }
    //     } else {
    //         if ($product->canAddToCart($qty, 0)) {
    //             $cart->addProduct($product, quantity: $qty);
    //             flash('Success', 'Added to cart');
    //         } else {
    //             flash('Error', 'Not enough stock');
    //             setSession('old', $_POST);
    //         }
    //     }

    //     redirect($redirectTo);
    // }
}
