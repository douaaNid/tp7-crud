<?php
require_once '../database/connexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

try {
    $stmt = $pdo->prepare('DELETE FROM modules WHERE id_module = :id');
    $stmt->execute([':id' => $id]);
    header('Location: index.php?msg=supprime');
} catch (PDOException $e) {
    header('Location: index.php?msg=erreur');
}
exit;
