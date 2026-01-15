<?php
require_once __DIR__ ."/Cart.php";
require_once __DIR__ ."/UA.php";
require_once __DIR__ ."/order.php";
require_once __DIR__ ."/ClassesPC.php";


//Product and cats
$cat1 = new Category('BBBB');
$cat2 = new Category('AAAA');
$cat3 = new Category('CVFFFFFF');

$product1 = new Product(1,"something", 5, 9867486, $cat1);
$product2 = new Product(2,'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',3, 49, $cat2);
$product3 = new Product(3,'this is something',2, 4984, $cat3);
$product4 = new Product(4,'avfegf',10, 1, $cat1);


//Addresses and User
$add1= new Address("12 aav", "Bordeaux", 46988, "France");
$add2= new Address("784 av", "Paris", 89954, "France");
$add3 = new Address("83 imp", "Los angeles", 4444, "USA");

$user1 = new User("Jean", "jean@exemple.com", time(), [$add1, $add2, $add3]);


//Cart
$cart = new Cart();
$cart->add($product1)
     ->add($product2, 3)
     ->remove(1)
     ->add($product3,6)
     ->add($product4, 4);


//Order
$order = new Order(1, $user1, $cart, "pending");


echo 'This is order '.$order->id.' created by: '.$order->user->name.' to be delivered at: '.$order->user->getDefaultAddress();
echo '<br>';
$orderContent = 'This order contains:';
foreach ($order->items->getItems() as $item) {
    $orderContent .= ' '.$item->item->getName(). '('.$item->quantity.')';
}
echo $orderContent;
echo '<br>';
echo $order->items->getTotalAllItems(). ' products. For a total of: '.$order->items->getTotal();




