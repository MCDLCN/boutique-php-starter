<?php
require_once __DIR__ . '/../../app/data.php';
function calculateIncludingTax(float $priceExcludingTax, float $rate = 20): float
{
    return $priceExcludingTax + calculateVAT($priceExcludingTax, $rate);
}
function calculateDiscount(float $price, float $percentage): float
{
    return $price * (1 - ($percentage / 100));
}
function calculateTotal(array $cart): float
{
    $total = 0;
    foreach ($cart as $product) {
        $total += $product['price'];
    }
    return $total;
}

function displayBadge(string $text, string $colour): string
{
    return '<span class="badge" style="background:'.$colour.'">'.$text.'</span>';
}
function formatPrice(float $amount): string
{
    return number_format($amount, 2, ",", " ").'$';
}

function formatDate(string $date): string
{
    return date('d F Y', strtotime($date));
}

function displayStock(int $quantity): string
{
    $colour = '';
    if ($quantity > 10) {
        $colour = "green";
    } elseif ($quantity <= 10 && $quantity > 0) {
        $colour = "orange";
    } else {
        $colour = "red";
    }
    //return '<span style="color:'.$colour.';"> There is '.$quantity.' left.';
    return $colour;
}

function isNew(string $dateAdded): bool
{
    return strtotime($dateAdded) > strtotime("now - 30 day");
}

function isOnSale(int $discount): bool
{
    return $discount > 0;
}

function displayAllBadges(array $product): string
{
    $badges = '';
    if (isNew($product["dateAdded"])) {
        $badges = $badges.'<span class="badge badge-pill bg-primary"> New </span>';
    }
    if (isOnSale($product["discount"])) {
        $badges = $badges.'<span class="badge badge-pill bg-primary"> On sale! </span>';
    }
    $badges = $badges.displayBadge(" ", displayStock($product['stock']));
    return $badges;
}

function validateEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePrice(mixed $price): bool
{
    return $price >= 0;
}

function dump_and_die(mixed ...$vars): void
{
    foreach ($vars as $var) {
        ob_start();
        var_dump($var);
        $value = ob_get_clean();

        echo '<pre style="background:#1e1e1e;color:#4ec9b0;padding:20px;border-radius:5px;">'
            . htmlspecialchars($value) .
            '</pre><br>';
    }
    die();
}


?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
<?php foreach ($products as $product) {
    echo displayAllBadges($product);
    echo '<br>';
}
echo formatPrice(calculateTotal($products));
echo '<br>';
echo validateEmail("example@gmail.com");
echo '<br>';
echo validatePrice(-5) ? "true" : "false";
echo '<br>';
echo formatDate($products[0]["dateAdded"]);
echo '<br>';
dump_and_die($products[0]);
?>

</body>
</html>