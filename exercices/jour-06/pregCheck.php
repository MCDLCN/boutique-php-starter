<?php

$telephone = $_POST['telephone'];

// Téléphone français : 10 chiffres commençant par 0
if (!preg_match('/^0[1-9][0-9]{8}$/', $telephone)) {
    echo 'Format téléphone invalide';
}



// Code postal français : 5 chiffres
// À toi : écris la regex pour valider un code postal

if (!preg_match('/^[0-9]{5}$/', $codePostal)) {
    echo 'Format invalide';
}

// Mot de passe fort : min 8 caractères, 1 majuscule, 1 chiffre, 1 spécial
// À toi : essaie de construire cette regex (difficile !)
// Indice : utilise plusieurs preg_match séparés, c'est plus lisible

if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $password)) {
    echo 'Mot de passe incorrect';
}

if (!preg_match('/^(?=.[a-z])(?=.[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $email)) {
    echo 'Invalid email';
}
