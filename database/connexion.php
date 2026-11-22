<?php
// Connexion PDO centralisée : à inclure avec require_once dans chaque page.
$host    = 'localhost';
$dbname  = 'tp7_crud';
$user    = 'root';   // utilisateur par défaut de XAMPP
$pass    = '';       // mot de passe vide par défaut

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . htmlspecialchars($e->getMessage()));
}
