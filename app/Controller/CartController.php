<?php
namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Entity\Cart;
use App\Database;
class CartController {
    public function index(){
        session_start();

        if (!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof Cart)) {
        $_SESSION['cart'] = new Cart();
        }
        $cart= $_SESSION['cart'];
        require __DIR__ . '/../../views/cart/index.php';
    }

    public function add(): void{
        session_start();

        $id = (int)($_POST['id'] ?? 0);
        $qty = max(1, (int)($_POST['qty'] ?? 1));

        $pdo= Database::getInstance();
        $catRepo= new CategoryRepository($pdo);
        $repo = new ProductRepository($pdo, $catRepo);

        $product = $repo->find($id);
        if (!$product) {http_response_code(404); exit('No such product');}
    
        if (!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof Cart)){
            $_SESSION['cart'] = new Cart();
        }

        $item = $_SESSION['cart']->getCartItem($id);
        if ($item !== null) {
            $current = $item->getQuantity();
            if ($product->canAddToCart($qty, $current)) {
                $item->setQuantity($current + $qty);
                $_SESSION['flash'] = 'Added to cart';}
            else {
            $_SESSION['flash'] = 'Not enough stock';}
        } 
        else {
            if ($product->canAddToCart($qty, 0)) {
                $_SESSION['cart']->addProduct($product, quantity: $qty);}
            else {
            $_SESSION['flash'] = 'Not enough stock';}
        }

        header('location: /cart'); exit;
    
    }

    public function remove(): void {
        session_start();
        $id = (int)($_POST['id'] ?? 0);

        if (isset($_SESSION['cart']) && $_SESSION['cart'] instanceof Cart) {
            $_SESSION['cart']->removeProduct($id);
        }
        header('Location: /cart'); exit;
    }
}