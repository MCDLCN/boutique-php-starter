<?php
class Product{
	function __construct(
		public int $id,
		public string $name,
		public string $description,
		public float $price,
		public int $stock,
		public string $category
	){}

	function getPriceIncludingTax(float $vat=20):float{
		return $this->price + ($this->price * $vat / 100);
	}

	function isInStock():bool{
		return $this->stock > 0;	
	}

	function reduceStock(int $amount):void{
		$this->stock -= $amount;
	}

	function applyDiscount(float $discount):float{
		return $this->price - ($this->price * $discount / 100);
	}
}