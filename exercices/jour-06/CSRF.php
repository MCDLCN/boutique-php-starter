<?php
session_start();

// 1. GÉNÉRER le token (une seule fois par session)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!-- 2. INCLURE dans le formulaire (champ caché) -->
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <!-- autres champs -->
    <button type="submit">Send</button>
</form>

<?php
// 3. VÉRIFIER à la réception
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Token CSRF invalide !');
    }
    // Traitement du formulaire...
}

// À toi : intègre cette protection dans ton formulaire de contact
