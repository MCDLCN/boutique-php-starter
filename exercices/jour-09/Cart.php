<?php
class Cart{
    public function __construct(
        public array $items = []
    ){}
    public function add(int $nb, string $something){
        $this->items[$nb] = $something;
    }

    public function remove(int $nb, Item $item){
        unset($this->items[$item]);
    }

    public function update(int $nb, string $something){
        $this->items[$nb] = $something;
    }

    public function getTotal(): int{
        $total=0;
        foreach($this->items as $key => $value){
            $total+=$key->price*$value;
        }
    }

    public function count() : int{
        return count($this->items);
    }

    public function clear() : void{
        $this->items = [];
    }
}

class Item{
    public function __construct(
        public int $nb,
        public string $name,
        public int $price
    ){
}

$cart = new Cart();
$cart->add(1,"95844");
$cart->add(2,"aaa");
$cart->add(3,"674687");
$cart->add(4,"szfafvzqgf");

$cart->update(4,"wwwwwwwwwwwwww");
$cart->remove(2);
$cart->getTotal();
$cart->clear();