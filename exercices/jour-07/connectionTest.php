<?php

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=boutique;charset=utf8mb4',
        'dev',
        'dev',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo '✅ Succesful log in !';
} catch (PDOException $e) {
    echo '❌ Error : ' . $e->getMessage();
}
