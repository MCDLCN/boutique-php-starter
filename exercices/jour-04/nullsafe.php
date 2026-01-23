<?php

class Product
{
    public function __construct(private ?string $category)
    {
    }
    public function getCategory()
    {
        return $this->category;
    }
}

$product = new Product('clothes');

$category = $product?->getCategory();

echo $category;
// SANS null safe : verbeux et répétitif
/*$country = null;
if ($user !== null) {
    $address = $user->getAddress();
    if ($address !== null) {
        $country = $address->getCountry();
    }
}
*/
// AVEC null safe : une seule ligne !
//$country = $user?->getAddress()?->getCountry();
// Si $user ou getAddress() est null, retourne null sans erreur

// À toi : récupère le nom de la catégorie d'un produit
// sachant que $produit->getCategorie() peut retourner null
