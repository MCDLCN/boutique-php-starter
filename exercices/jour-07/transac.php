<?php
$pdo->beginTransaction();

try {
    // 1. Créer la commande
    $stmt = $pdo->prepare("INSERT INTO commandes (user_id, total) VALUES (?, ?)");
    $stmt->execute([$userId, $total]);
    $commandeId = $pdo->lastInsertId();
    
    // 2. Décrémenter le stock de chaque produit
    // À toi : UPDATE produits SET stock = stock - ? WHERE id = ?
    $stmt = $pdo->prepare("UPDATE produits SET stock = stock - ? WHERE id = ?");
    $stmt->execute([$quantite, $produitId]);

    $stmt = $pdo->prepare("INSERT INTO commandes_produits (commande_id, produit_id, quantite, prix) VALUES (?, ?, ?, ?)");
    $stmt->execute([$commandeId, $produitId, $quantite, $prix]);

    // 3. Si tout est OK, valider
    $pdo->commit();
    
} catch (Exception $e) {
    // 4. Si erreur, annuler TOUT
    $pdo->rollBack();
    throw $e;
}

// À toi : ajoute l'insertion des lignes de commande dans la transaction