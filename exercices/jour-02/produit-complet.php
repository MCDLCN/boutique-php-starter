<?php
$product = [
    'name' => 'AMOGUS',
    'images' => ['https://www.google.com/url?sa=t&source=web&rct=j&url=https%3A%2F%2Famogus.fandom.com%2Fwiki%2FAmogus&opi=89978449', 'https://upload.wikimedia.org/wiktionary/en/3/39/Amogus_non-free.png', 'https://www.google.com/url?sa=t&source=web&rct=j&url=https%3A%2F%2Fen.wiktionary.org%2Fwiki%2Famogus&opi=89978449'],
    'sizes' => ['S', 'M', 'L', 'XL'],
    'reviews' => [
        ['author' => 'AAA', 'rating' => 5, 'comment' => 'Very much AAAAAAAAAA'],
        ['author' => 'BBB', 'rating' => 2, 'comment' => 'Not enough BBBBBB'],
        ['author' => 'CCC', 'rating' => 3, 'comment' => 'I like AAA but I prefer BBBB'],
    ]
];
?>
<!DOCTYPE html>
<html>
<body>

<h1><?= $product['name'] ;?></h1>
<br>
<p>Image: <img src="<?= $product['images'][1] ?>" alt="<?= htmlspecialchars($product['name']) ?>"></p>
<br>
<p>Sizes: <?php foreach ($product['sizes'] as $key) {
    echo $key.', ';
}?></p>
<br>
<p>Rating 1st: <?php echo $product['reviews'][0]['rating'];?></p>
<br>
</body>
</html> 