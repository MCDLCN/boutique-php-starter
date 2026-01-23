<?php

if (isset($_FILES['image'])) {
    $file = $_FILES['image'];

    // 1. Vérifier les erreurs d'upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("Erreur d'upload");
    }

    // 2. Vérifier le type MIME (pas juste l'extension !)
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    // À toi : vérifie que $file['type'] est dans la liste
    if (!in_array($file['type'], $allowedTypes)) {
        die('Image type not allowed');
    }
    // 3. Vérifier la taille (ex: max 2 Mo)
    // À toi : vérifie que $file['size'] < 2 * 1024 * 1024
    if ($file['size'] > 2 * 1024 * 1024) {
        die('Image too big');
    }
    // 4. Générer un nom unique (JAMAIS utiliser le nom d'origine !)
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nouveauNom = uniqid('produit_') . '.' . $extension;

    // 5. Déplacer le fichier
    move_uploaded_file($file['tmp_name'], 'uploads/' . $nouveauNom);
}
