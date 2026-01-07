<?php
// Au lieu de :
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Tu peux écrire :
$_SESSION['panier'] ??= [];

// Autre exemple avec une config
$config['debug'] ??= false; // false seulement si pas défini

// À toi : initialise ces variables de session si elles n'existent pas
// - $_SESSION['visites'] → 0
// - $_SESSION['derniere_page'] → '/'
// - $_SESSION['theme'] → 'light'
$_SESSION['visites'] ??= 0;
$_SESSION['laste page'] ??= '';
$_SESSION['theme'] ??= 'light';

var_dump($_SESSION);