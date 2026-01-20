<?php
function calculateIncludingTax(float $priceExcludingTax, float $rate = 20): float {
    return $priceExcludingTax + calculateVAT($priceExcludingTax, $rate);
}
function calculateDiscounted(float $price, float $percentage): float {
    return $price * (1 - ($percentage / 100));
}
function calculateTotal(array $cart) : float{
	$total=0;
	foreach ($cart as $product) {
		$total+=$product['price'];
	}
	return $total;
}


function displayBadge(string $text, string $colour) : string {
	return '<span class="badge" style="background:'.$colour.'">'.$text.'</span>';
}
function truncate(float $value, int $decimals = 2): float {
    $factor = 10 ** $decimals;
    return floor($value * $factor) / $factor;
}
function formatPrice(float $amount): string {
	$amount=truncate($amount);
    $amount = floor($amount * 100) / 100;
    return number_format($amount, 2, ',', ' ') . '$';
}

function formatDate (string $date) : string {
	return date('d F Y', strtotime($date));
}

function displayStock(int $quantity) : array {
	$colour='';
	$text='';
	if($quantity>10){
		$colour="green";
		$text="in stock";
	}elseif ($quantity<=10 && $quantity >0) {
		$colour="orange";
		$text="few remaining";
	}
	else{$colour="red";
		 $text="out of stock";}
	//return '<span style="color:'.$colour.';"> There is '.$quantity.' left.';
	return [$text,$colour];
}

function isNew(string $dateAdded) : bool {
	return strtotime($dateAdded) > strtotime("now - 30 day");
}

function isOnSale(int $discount) : bool {
	return $discount>0;
}

// function displayAllBadges(array $product) : string {
// 	$badges= '';
// 	if (isNew($product["dateAdded"])){
//        $badges=$badges.'<span class="badge badge-pill bg-primary"> New </span>';}
//     if (isOnSale($product["discount"])){
//       	$badges=$badges.'<span class="badge badge-pill bg-primary"> On sale! </span>';}
//     $badges= $badges.displayBadge(" ",displayStock((int)$product['stock']));
//     return $badges;
// }

function validateEmail(string $email) : bool {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePrice (mixed $price) : bool {
	return $price>=0;
}

function dump_and_die(mixed ...$vars): void {
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

function view(string $template, array $data = []): void
{
    extract($data); // Transforme ['title' => 'X'] en $title = 'X'
    
    ob_start();
    require __DIR__ . "/../views/$template.php";
    $content = ob_get_clean();
    
    require __DIR__ . '/../views/layout.php';
}

// Helper de redirection
function redirect(string $url): void
{
    header("Location: $url");
    exit;
}

function session(string $key, mixed $default = null): mixed
{
    return $_SESSION[$key] ?? $default;
}

// Écrire une valeur en session
function setSession(string $key, mixed $value): void
{
    $_SESSION[$key] = $value;
}

// Créer un flash message
function flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,      // 'success', 'error', 'warning'
        'message' => $message
    ];
}

// Récupérer et supprimer le flash message
function getFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']); // Supprime après lecture
    return $flash;
}

// Récupérer l'ancienne valeur d'un champ
function old(string $key, string $default = ''): string
{
    return $_SESSION['old'][$key] ?? $default;
}


function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function pageUrl(int $page): string {
    $params = $_GET;
    $params['page'] = $page;

    $basePath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // e.g. /catalog
    return $basePath . '?' . http_build_query($params);
}

function getCart(): \App\Entity\Cart
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof \App\Entity\Cart)) {
        $_SESSION['cart'] = new \App\Entity\Cart();
    }

    return $_SESSION['cart'];
}