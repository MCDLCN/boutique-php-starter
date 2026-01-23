<?php
$options = [
    'options' => [
        'min_range' => 1,
        'max_range' => 99
    ]
];

$stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT, $options);

if ($stock === false) {
    echo 'Quantité invalide (doit être entre 1 et 99)';
}

// À toi : valide un prix entre 0.01 et 9999.99
// Indice : utilise FILTER_VALIDATE_FLOAT avec des options

$options2 = [
  'options' => [
     'min_range' => 0.01,
     'max_range' => 9999.99
    ]
];

$quantity = filter_var($_POST['stock'], FILTER_VALIDATE_FLOAT, $options2);

if ($quantity === false) {
    echo 'Quantité invalide (doit être entre 0.01 et 9999.99)';
}

$options3 = [
    'price' => [
        'min_range' => 0.01,
        'max_range' => 100000000
    ],
    'stock' => [
        'min_range' => 0,
        'max_range' => 100000000
    ]];
$data = [
 'name' => $_POST['name'],
 'price' => $_POST['price'],
 'stock' => $_POST['stock']
];

function validateProductForm($data)
{
    $errors = [];
    if (preg_match('/^[a-z]*$/i', $data['name']) === 0) {
        $errors[] = 'Invalid name';
    }
    if (filter_var($data['price'], FILTER_VALIDATE_FLOAT) === false) {
        $errors[] = 'Invalid price';
    }
    if (filter_var($data['stock'], FILTER_VALIDATE_INT) === false) {
        $errors[] = 'Invalid stock';
    }
    return $errors;
}

?>
<span>
<form action="" method="post">
<input type="text" name="name">
<input type="text" name="price">
<input type="text" name="stock">
<input type="submit">
<ul>
<?php foreach (validateProductForm($data) as $error): ?>
<li><?= $error ?></li>
<?php endforeach; ?>
</ul>
</form>
</span>

