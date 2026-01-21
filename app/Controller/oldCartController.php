<?php
//namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Entity\Cart;
use App\Database;
class CartController {
    public function index(){
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();

        if (!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof Cart)) {
        $_SESSION['cart'] = new Cart();
        }
        $cart= $_SESSION['cart'];

        $cart = getCart();

        $pdo = Database::getInstance();

        $categoryRepo = new CategoryRepository($pdo);
        $productRepo  = new ProductRepository($pdo, $categoryRepo);


        foreach (array_keys($cart->getItems()) as $id) {
            $product = $productRepo->find((int)$id);
            if ($product === null) {
                $cart->removeProduct((int)$id);
                continue;
            }
            $cart->setProduct($product);
        }

        $totalCart = $cart->getTotal();

        $freeDelivery = $totalCart > 50;

        $amountItems =getCart()->countAllItems();

        view('cart/index',['cart'=>$cart,'totalCart'=>$totalCart,'freeDelivery'=>$freeDelivery,'amountItems'=>$amountItems,'currentlyHere'=>'cart']);
    }

    public function add(): void{
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();

        $id = (int)($_POST['idCart'] ?? 0);
        $qty = max(1, (int)($_POST['quantityAdd'] ?? 1));

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
                flash('Success', 'Added to cart');
            }
            else {
            flash('Error', 'Not enough stock');
            }
        } 
        else {
            if ($product->canAddToCart($qty, 0)) {
                $_SESSION['cart']->addProduct($product, quantity: $qty);
                flash('Success', 'Added to cart');}
            else {
            flash('Error', 'Not enough stock');
            }
        }

        $redirect = $_POST['redirect'] ?? '/catalog';
        redirect($redirect);
        exit;
    
    }

    public function remove(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $id = (int)($_POST['id'] ?? 0);

        if (isset($_SESSION['cart']) && $_SESSION['cart'] instanceof Cart) {
            $_SESSION['cart']->removeProduct($id);
        }
        redirect('/cart'); 
        exit;
    }

    public function empty(): void{
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $_SESSION['cart']->clear();
        flash('Success', 'Cart emptied');
        redirect('/cart'); 
        exit;
    }

    public function update():void {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();

        $id = (int)($_POST['idCart'] ?? 0);
        $qty = max(1, (int)($_POST['quantityUpdate'] ?? 1));

        $pdo= Database::getInstance();
        $catRepo= new CategoryRepository($pdo);
        $repo = new ProductRepository($pdo, $catRepo);

        $product = $repo->find($id);
        if (!$product) {http_response_code(404); exit('No such product');}

        if ($product->getStock()<$qty) {flash('Error', 'Not enough stock');}
        else{$_SESSION['cart']->getCartItem($id)->setQuantity($qty);
        flash('Success', 'Quantity updated');}

        $redirect = $_POST['redirect'] ?? '/catalog';
        
        redirect($redirect);
        exit;
    }
}