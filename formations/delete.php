<?php
require_once '../database/connexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

try {
    $stmt = $pdo->prepare('DELETE FROM formations WHERE id_formation = :id');
    $stmt->execute([':id' => $id]);
    header('Location: index.php?msg=supprime');
} catch (PDOException $e) {
    // 23000 : violation de contrainte (la formation a encore des étudiants ou des modules)
    header('Location: index.php?msg=' . ($e->getCode() === '23000' ? 'lie' : 'inconnu'));
}
exit;
