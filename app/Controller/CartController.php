<?php

namespace App\Controller;

use App\Database;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Entity\CartItem;

class CartController extends Controller
{
    public function index(): void
    {


        $cart = getCart();

        $pdo = Database::getInstance();

        $categoryRepo = new CategoryRepository($pdo);
        $productRepo  = new ProductRepository($pdo, $categoryRepo);


        foreach (array_keys($cart->getItems()) as $id) {
            $product = $productRepo->find((int)$id);
            if (!$product instanceof \App\Entity\Product) {
                $cart->removeProduct((int)$id);
                continue;
            }
            $cart->setProduct($product);
        }

        $totalCart = $cart->getTotal();

        $freeDelivery = $totalCart > 50;

        $amountItems = $cart->countAllItems();

        $this->view('cart/index', ['cart' => $cart,'totalCart' => $totalCart,'freeDelivery' => $freeDelivery,'amountItems' => $amountItems,'currentlyHere' => 'cart']);
    }

    public function add(): void
    {
        $cart = getCart();

        $id = (int)($_POST['idCart'] ?? 0);
        $qty = max(1, (int)($_POST['quantityAdd'] ?? 1));

        $pdo = Database::getInstance();
        $catRepo = new CategoryRepository($pdo);
        $repo = new ProductRepository($pdo, $catRepo);

        $product = $repo->find($id);
        if (!$product instanceof \App\Entity\Product) {
            http_response_code(404);
            exit('No such product');
        }


        $item = $cart->getCartItem($id);
        if ($item instanceof \App\Entity\CartItem) {
            $current = $item->getQuantity();
            if ($product->canAddToCart($qty, $current)) {
                $item->setQuantity($current + $qty);
                flash('Success', 'Added to cart');
            } else {
                flash('Error', 'Not enough stock');
            }
        } elseif ($product->canAddToCart($qty, 0)) {
            $cart->addProduct($product, quantity: $qty);
            flash('Success', 'Added to cart');
        } else {
            flash('Error', 'Not enough stock');
        }

        $redirect = $_POST['redirect'] ?? '/catalog';
        $this->redirect($redirect);
        exit;

    }

    public function remove(): void
    {
        $cart = getCart();
        $id = (int)($_POST['idRemove'] ?? 0);

        if ($cart->getCartItem($id) instanceof CartItem) {
            $cart->removeProduct($id);
            flash('Success','Object removed');
        }
        $this->redirect('/cart');
        exit;
    }

    public function empty(): void
    {
        $cart = getCart();
        $cart->clear();
        flash('Success', 'Cart emptied');
        $this->redirect('/cart');
        exit;
    }

    public function update(): void
    {
        $cart = getCart();

        $id = (int)($_POST['idCart'] ?? 0);
        $qty = max(1, (int)($_POST['quantityUpdate'] ?? 1));

        $pdo = Database::getInstance();
        $catRepo = new CategoryRepository($pdo);
        $repo = new ProductRepository($pdo, $catRepo);

        $product = $repo->find($id);
        if (!$product instanceof \App\Entity\Product) {
            http_response_code(404);
            exit('No such product');
        }

        if ($product->getStock() < $qty) {
            flash('Error', 'Not enough stock');
        } else {
            $cart->getCartItem($id)->setQuantity($qty);
            flash('Success', 'Quantity updated');
        }

        $redirect = $_POST['redirect'] ?? '/catalog';

        $this->redirect($redirect);
        exit;
    }
}
