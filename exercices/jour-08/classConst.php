<?php

class Order
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';

    public function __construct(
        private string $status = self::STATUS_PENDING
    ) {
    }

    public function canBeCancelled(): bool
    {
        // À toi : on peut annuler seulement si pending ou paid
        return $this->status === self::STATUS_PENDING || $this->status === self::STATUS_PAID;
    }

    public static function getAllStatuses(): array
    {
        return [
            self::STATUS_PENDING, self::STATUS_PAID, self::STATUS_SHIPPED, self::STATUS_DELIVERED, self::STATUS_CANCELLED
        ];
    }
}

// Utilisation
$order = new Order();
if ($order->canBeCancelled()) {
    echo 'Can be cancelled';
}
echo '<br>';
// À toi : crée une classe Product avec des constantes de TVA
// TVA_STANDARD = 20, TVA_REDUCED = 5.5, TVA_SUPER_REDUCED = 2.1

class Product
{
    public const TVA_STANDARD = 20;
    public const TVA_REDUCED = 5.5;
    public const TVA_SUPER_REDUCED = 2.1;

    public function __construct(
        private float $price,
        public string $name
    ) {
    }

    public function priceTax(): float
    {
        return $this->price * (1 + self::TVA_STANDARD / 100);
    }

}

$p = new Product(100, 'T-Shirt');
echo $p->priceTax();


foreach (Order::getAllStatuses() as $status) {
    echo $status . '<br>';
}
